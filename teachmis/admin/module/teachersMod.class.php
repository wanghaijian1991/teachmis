<?php
//用户管理
class teachersMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('teachers')->teachrsCount($_GET['search']);
		//获取信息列表
        $list=model('teachers')->teachersList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='教师列表';
        $this->page=$this->page($url,$num,$listRows);
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