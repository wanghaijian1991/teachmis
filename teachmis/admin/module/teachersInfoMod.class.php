<?php
//用户管理
class teachersInfoMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $info=model('teachers')->info($_SESSION["user_yg"]["id"]);
        $this->assign("info",$info);
        $this->action_name='编辑教师';
        $this->action='edit';
        $this->show();
	}

	//用户添加
	public function add() {
        $this->list=model('groupinfo')->admin_list();
		$this->action_name='添加教师';
        $this->action='add';
        $this->show('teachers/info');
	}

	public function add_save() {
        if(!$_POST['teacherPassword']){
            $this->msg('密码不能为空！',1);
            return;
        }
        $key=rand(10000,99999);
        $_POST['teacherPassword']=$this->md5ps($_POST['teacherPassword'],$key);
        $_POST['teacherSecretKey']=$key;
        //录入模型处理
        model('teachers')->add($_POST);
        $this->msg('添加教师成功！',0);
	}

    public function edit() {
        $this->list=model('groupinfo')->admin_list();
        $id=$_GET['id'];
        $info=model('teachers')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑教师';
        $this->action='edit';
        $this->show('teachers/info');
    }

    public function edit_save() {
        if(!$_POST['teacherPassword']){
            unset($_POST['teacherPassword']);
        }else{
            $key=rand(10000,99999);
            $_POST['teacherPassword']=$this->md5ps($_POST['teacherPassword'],$key);
            $_POST['teacherSecretKey']=$key;
        }

        //录入模型处理
        model('teachers')->edit($_POST);
        $this->msg('修改教师成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_POST['id']);
        //录入模型处理
        $status=model('teachers')->del($id);
        $this->msg('删除教师成功！',0);
    }

}