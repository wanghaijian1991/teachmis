<?php
class loginMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }

    // 登录页面
    public function index()
    {
        if($_SESSION["user_yg"]["id"])
        {
            $this->display("index/index");
        }else{
            $this->display("login/index");
        }
    }

    // 登录页面
    public function login()
    {
        if($_SESSION["user_yg"]["id"])
        {
            $this->display("index/index");
        }else{
            $this->display("login/index");
        }
    }

    //登陆检测
    public function check(){
        if(empty($_POST['user'])||empty($_POST['password'])){
            $this->msg('帐号信息输入错误!',0);
        }
        //获取帐号信息
        if($_POST["userType"]==2)
        {
            $info=$this->model->table('users')->where('usersName="'.$_POST['user'].'"')->find();
            //进行帐号验证
            if(empty($info)){
                $this->msg('登录失败! 无此帐号!',0);
            }
            $pass=$this->md5ps($_POST['password'],$info['usersSecretKey']);
            if($info['usersPassword']<>$pass){
                $this->msg('登录失败! 密码错误!',0);
            }
            /*$att=$this->model->table("department")->where("id=".$info["fid"])->find();
            $_SESSION["user_yg"]["department"]=$att["name"];*/
            $_SESSION["user_yg"]["id"]=$info["usersId"];
            $_SESSION["user_yg"]["schoolId"]=$info["schoolId"];
            $_SESSION["user_yg"]["userType"]=2;
            //更新帐号信息
            $data['logintime']=time();
            $data['loginnum']=intval($info['loginnum'])+1;
            $id=$info['usersId'];
            $this->model->table('users')->data($data)->where($id)->update();
            //设置登录信息
            $_SESSION[$this->config['SPOT'].'_user']=cp_encode($info['usersId'],$this->config['DB_PREFIX']);
        }else if($_POST["userType"]==1)
        {
            $info=$this->model->table('teacher')->where('teacherName="'.$_POST['user'].'"')->find();
            //进行帐号验证
            if(empty($info)){
                $this->msg('登录失败! 无此帐号!',0);
            }
            $pass=$this->md5ps($_POST['password'],$info['teacherSecretKey']);
            if($info['teacherPassword']<>$pass){
                $this->msg('登录失败! 密码错误!',0);
            }
            /*$att=$this->model->table("department")->where("id=".$info["fid"])->find();
            $_SESSION["user_yg"]["department"]=$att["name"];*/
            $_SESSION["user_yg"]["id"]=$info["teacherId"];
            $_SESSION["user_yg"]["schoolId"]=$info["schoolId"];
            $_SESSION["user_yg"]["userType"]=1;
            //更新帐号信息
            $data['logintime']=time();
            $data['loginnum']=intval($info['loginnum'])+1;
            $id=$info['teacherId'];
            $this->model->table('teacher')->data($data)->where($id)->update();
            //设置登录信息
            $_SESSION[$this->config['SPOT'].'_user']=cp_encode($info['teacherId'],$this->config['DB_PREFIX']);
        }else{
            $info=$this->model->table('admininfo')->where('adminname="'.$_POST['user'].'"')->find();
            //进行帐号验证
            if(empty($info)){
                $this->msg('登录失败! 无此帐号!',0);
            }
            if($info['password']<>md5($_POST['password'])){
                $this->msg('登录失败! 密码错误!',0);
            }
            /*$att=$this->model->table("department")->where("id=".$info["fid"])->find();
            $_SESSION["user_yg"]["department"]=$att["name"];*/
            $_SESSION["user_yg"]["id"]=$info["id"];
            $_SESSION["user_yg"]["schoolId"]=$info["id"];
            $_SESSION["user_yg"]["userType"]=0;
            //更新帐号信息
            $data['logintime']=time();
            $data['loginnum']=intval($info['loginnum'])+1;
            $id=$info['id'];
            $this->model->table('admininfo')->data($data)->where($id)->update();
            //设置登录信息
            $_SESSION[$this->config['SPOT'].'_user']=cp_encode($info['id'],$this->config['DB_PREFIX']);
        }
        $this->msg('登录成功!',0);
        
    }

    //退出
     public function logout(){
        /*if(empty($_POST)){
            $this->redirect(__APP__);
        }*/
        $_SESSION=array();
        $this->msg('退出成功! ',0);
     }

     function updatePosition()
     {
         $data["position_lng"]=$_GET["Lng"];
         $data["position_lat"]=$_GET["Lat"];
         $data["create_time"]=date("Y-m-d H:i:s");
         $this->model->table('position')->data($data)->insert();
         /*echo "123";
         $re=[
             "errno"=>0,
             "message"=>"秒杀活动时间段"
         ];
         echo $_GET["callback"]."(".json_encode($re, JSON_UNESCAPED_UNICODE).")";*/
     }

     function selectPosition()
     {
         $arr=$this->model->table('position')->select();
         $datas=array();
         foreach($arr as $v)
         {
             $data=array();
             $data[]=$v["position_lng"];
             $data[]=$v["position_lat"];
             $datas[]=$data;
         }
         $info["name"]="济南 -> 济宁";
         $info["path"]=$datas;
         $infos[]=$info;
         echo json_encode($infos);
         /*$re["errno"]=0;
         $re["message"]="获取成功！";
         $re["data"]=$info;
         echo $_GET["callback"]."(".json_encode($re).")";*/
     }



}

?>
