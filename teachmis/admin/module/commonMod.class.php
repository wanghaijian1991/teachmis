<?php
//公共类
class commonMod
{
    protected $model = NULL; //数据库模型
    protected $layout = NULL; //布局视图
    protected $config = array();
    private $_data = array();
    public $schoolId='';
    public $userId='';

    protected function init(){}
    
    public function __construct(){
        global $config;
        @session_start();
        $this->config = $config;
        $this->model = self::initModel( $this->config);
        $this->menu_list=model('menu')->main_menu();
        $this->appurl=__APP__;
        $this->schoolId=$_SESSION["user_yg"]["schoolId"];
        $this->userId=$_SESSION["user_yg"]["id"];
        $this->init();
        if($_GET["_module"]!='login')
        {
            $this->check_login();
        }
    }


    //初始化模型
    static public function initModel($config){
        static $model = NULL;
        if( empty($model) ){
            $model = new cpModel($config);
        }
        return $model;
    }

    public function __get($name){
        return isset( $this->_data[$name] ) ? $this->_data[$name] : NULL;
    }

    public function __set($name, $value){
        $this->_data[$name] = $value;
    }


    //获取模板对象
    public function view(){
        static $view = NULL;
        if( empty($view) ){
            $view = new cpTemplate( $this->config );
        }
        return $view;
    }
    
    //模板赋值
    protected function assign($name, $value){
        return $this->view()->assign($name, $value);
    }

    //模板显示
    protected function display($tpl = '', $return = false, $is_tpl = true ,$diy_tpl=false){
        if( $is_tpl ){
            $tpl = empty($tpl) ? $_GET['_module'] . '/'. $_GET['_action'] : $tpl;
            if( $is_tpl && $this->layout ){
                $this->__template_file = $tpl;
                $tpl = $this->layout;
            }
        }

        $this->assign("model", $this->model);
        $this->assign('sys', $this->config);
        $this->assign('config', $this->config);
        $this->assign('js', $this->load_js());
        $this->assign('css', $this->load_css());
        $this->assign('admin', model('admin')->current_user());
        $this->view()->assign( $this->_data );
        return $this->view()->display($tpl, $return, $is_tpl,$diy_tpl);
    }

    //包含内模板显示
    protected function show($tpl = ''){
        $content=$this->display($tpl,true);
        $body=$this->display('index/common',true);
        $html=str_replace('<!--body-->', $content, $body);
        echo $html;
    }

    //脚本运行时间
    public function runtime(){
    $GLOBALS['_endTime'] = microtime(true);
        $runTime = number_format($GLOBALS['_endTime'] - $GLOBALS['_startTime'], 4);
        echo $runTime;
    }


    //判断是否是数据提交 
    protected function isPost(){
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    //登录检测
    protected function check_login() {

        if($_GET['_module']=='login' || substr($_GET['_action'],-6)=='ignore'){
            return true;exit;
        }
        if(!empty($_GET['key'])){
            $key=urldecode($_GET['key']);
            $syskey=$this->config['SPOT'].$this->config['DB_NAME'];
            if($key==$syskey){
                return true;
            }
        }
        $code=$_SESSION[$this->config['SPOT'].'_user'];
        $uid=cp_decode($code,$this->config['DB_PREFIX']);
        //读取登录信息
        if(empty($uid)){
            $uid=0;
            header('location:' . __APP__ . '/login');
        }
        if($_SESSION["user_yg"]["userType"]==2)
        {
            $user=$this->model->table('users')->where('usersId='.$uid)->find();
        }else if($_SESSION["user_yg"]["userType"]==1)
        {
            $user=$this->model->table('teacher')->where('teacherId='.$uid)->find();
        }else{
            $user=$this->model->table('admininfo')->where('id='.$uid)->find();
        }
        if(empty($user)){
            $this->redirect(__APP__ . '/login');
        }
        $this->check_pw($user);
        return true;
    }

    //检测模块权限
    protected function check_pw($user){
        if($user['keep']==1){
            return true;
        }
        
        if(empty($user['model_power'])){
            return true;
        }
        $module=in($_GET['_module']);
        //处理栏目权限
        if(substr($module,-8)=='category'){
            $module='category';
        }
        $info=model('menu')->module_menu($module);
        if(!in_array($info['id'], $user['model_power'])){
            $this->msg('您没有权限进行操作！');
        }
    }

    //直接跳转
    protected function redirect($url)
    {
        header('location:' . $url, false, 301);
        exit;
    }

    //操作成功之后跳转,默认三秒钟跳转
    protected function success($msg, $url = null, $waitSecond = 3)
    {
        if ($url == null)
            $url = __URL__;
        $this->assign('message', $this->getlang($msg));
        $this->assign('url', $url);
        $this->assign('waitSecond', $waitSecond);
        $this->display('success');
        exit;
    }

    //弹出信息
    protected function alert($msg, $url = NULL){
        header("Content-type: text/html; charset=utf-8"); 
        $alert_msg="alert('$msg');";
        if( empty($url) ) {
            $gourl = 'history.go(-1);';
        }else{
            $gourl = "window.location.href = '{$url}'";
        }
        echo "<script>$alert_msg $gourl</script>";
        exit;
    }

    //判断空值
    public function alert_str($srt,$type=0,$json=false)
    {
        switch ($type) {
            case 'int':
                $srt=intval($srt);
                break;
            default:
                $srt=in($srt);
                break;
        }
        if(empty($srt)){
            if($json){
                $this->msg('内部通讯错误！',0);
            }else{
                $this->alert('内部通讯错误！');
            }
        }
    }

    //提示
    public function msg($message,$status=1,$data=array()) {
        //@header("Content-type:application/json,text/html,*/*");
        echo json_encode(array('status' => $status, 'message' => $message, 'data' => $data));
        exit;
    }

    //JSUI库
    public function load_js() {
        $js='';
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/jquery.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/duxui.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/dialog/lhgdialog.min.js?skin=default"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/kindeditor-min.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/kindeditor/lang/zh_CN.js"></script>' . PHP_EOL;
        $js .= '<script type=text/javascript src="' . __PUBLICURL__ . '/js/common.js"></script>' . PHP_EOL;
        return $js;
    }
    //CSSUI库
    public function load_css()
    {
        $css='';
        $css .= '<link href="' . __PUBLICURL__ . '/css/base.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        $css .= '<link href="' . __PUBLICURL__ . '/css/style.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        $css .= '<link href="' . __PUBLICURL__ . '/kindeditor/themes/default/default.css" rel="stylesheet" type="text/css" />' . PHP_EOL;
        return $css;
    }

    //分页 $url:基准网址，$totalRows: $listRows列表每页显示行数$rollPage 分页栏每页显示的页数
    protected function page($url, $totalRows, $listRows =20, $rollPage = 5)
    {
        $page = new Page();
        return $page->show($url, $totalRows, $listRows, $rollPage);
    }

    //获取分页条数
    protected function pagelimit($url,$listRows)
    {
        $page = new Page();
        $cur_page = $page->getCurPage($url);
        $limit_start = ($cur_page - 1) * $listRows;
        return  $limit_start . ',' . $listRows;
    }

    //插件接口
    public function plus_hook($module,$action,$data=NULL,$return=false)
    {
        $action_name='hook_'.$module.'_'.$action;
        if(!empty($list)){
            foreach ($list as $value) {
                $action_array=$plugin_list[$value['file']];
                if(!empty($action_array)){
                    if(in_array($action_name,$action_array))
                    {
                        if($return){
                            return Plugin::run($value['file'],$action_name,$data,$return);
                        }else{
                            Plugin::run($value['file'],$action_name,$data);
                        }
                    }
                }
            }
        }
    }

        

    //替换插件接口
    public function plus_hook_replace($module,$action,$data=NULL)
    {
        $hook_replace=$this->plus_hook($module,$action,$data,true);
        if(!empty($hook_replace)){
            return $hook_replace;
        }else{
            return $data;
        }
    }

    function md5ps($password,$key){
        return md5(md5($password)."teachmis".$key);
    }

    /**
     * 验证数据
     * field验证字段 name字段名称 type验证类型 prompt错误提示
     * 1 验证是否为空
     * 2 验证手机号
     * 3 验证邮箱
    */
    function validation_field($arr,$data)
    {
        $return['status']=0;
        $return['msg']='验证成功！';
        if(!$arr)
        {
            $return['status']=1;
            $return['msg']='验证数据异常！';
        }
        foreach($arr as $v)
        {
            switch ($v['type']){
                case 1:
                    if(empty($data[$v['field']]))
                    {
                        $return['status']=1;
                        $return['msg']=$v['prompt'];
                    }
                    break;
                case 2:
                    $status=false;
                    $g = "/^1[34578]\d{9}$/";
                    $g2 = "/^19[89]\d{8}$/";
                    $g3 = "/^166\d{8}$/";
                    if(preg_match($g, $data[$v['field']])){
                        $status=true;
                    }else  if(preg_match($g2, $data[$v['field']])){
                        $status=true;
                    }else if(preg_match($g3, $data[$v['field']])){
                        $status=true;
                    }
                    if(!$status)
                    {
                        $return['status']=1;
                        $return['msg']=$v['prompt'];
                    }
                    break;
                case 3:
                    $result  = filter_var($data[$v['field']], FILTER_VALIDATE_EMAIL);
                    if(!$result)
                    {
                        $return['status']=1;
                        $return['msg']=$v['prompt'];
                    }
            }
        }
        return $return;
    }


}
