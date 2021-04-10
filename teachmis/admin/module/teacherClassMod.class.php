<?php
//我的管理
class teacherClassMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        $where['teacherId']=$_SESSION["user_yg"]["id"];
        //获取行数
        $num = model('users')->usersCount($where);
        //获取信息列表
        $list = model('users')->usersList($where, $limit);
        $this->assign('list', $list);
        $this->menu_name = '学生列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
    /**
     * 查看详情
    */
    public function info()
    {
        $id=$_GET['id'];
        $this->classlist=model('classs')->list_select('');
        $this->grade=model("grade")->list_select('');
        $this->list=model('groupinfo')->admin_list();
        $info=model('users')->info($id);
        $this->assign("info",$info);
        $this->action_name='编辑学生';
        $this->action='edit';
        $this->show();
    }
    /**
     * 查看班级成绩曲线
    */
    public function getrejectedclass()
    {}
    /**
     * 导入学生成绩
    */
    public function importrejected()
    {
        //接收前台文件
        $ex = $_FILES['excel'];print_r($ex);
        //重设置文件名
        $filename = time().substr($ex['name'],stripos($ex['name'],'.'));
        $path = __ROOTDIR__.'/excel/'.$filename;//设置移动路径
        move_uploaded_file($ex['tmp_name'],$path);echo $path;
        //表用函数方法 返回数组
        $exfn = $this->_readExcel($path);print_r($exfn);

        $this->redirect('input');
    }
    //创建一个读取excel数据，可用于入库
    public function _readExcel($path)
    {
        //引用PHPexcel 类
        include_once(CP_PATH.'PHPExcel/PHPExcel.php');
        include_once(CP_PATH.'PHPExcel/PHPExcel/IOFactory.php');//静态类
        $type = 'Excel2007';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
        $xlsReader = PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($path);
        //开始读取上传到服务器中的Excel文件，返回一个二维数组
        $dataArray = $Sheets->getSheet(0)->toArray();
        return $dataArray;
    }
    /**
     * 查看学生成绩
    */
    public function getrejected()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        $where['teacherId']=$_SESSION["user_yg"]["id"];
        //获取行数
        $num = model('teacherClass')->usersCount($where);
        //获取信息列表
        $list = model('teacherClass')->usersList($where, $limit);
        $this->assign('list', $list);
        $userInfo=model('users')->info($_GET['id']);
        $this->menu_name = $userInfo['studentName'].'学生成绩列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
}
?>