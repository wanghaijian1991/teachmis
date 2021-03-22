<?php
//用户管理
class usersMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('users')->usersCount($_GET['search']);
		//获取信息列表
        $list=model('users')->usersList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='学生列表';
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
        $this->classlist=model('classs')->list_select('');
        $this->grade=model("grade")->list_select('');
        $this->list=model('groupinfo')->admin_list();
		$this->action_name='添加学生';
        $this->action='add';
        $this->show('users/info');
	}

	public function add_save() {
        $validation=array(
            array('field'=>'gid','name'=>'权限组','type'=>1,'prompt'=>'权限组不能为空！'),
            array('field'=>'usersName','name'=>'用户名','type'=>1,'prompt'=>'用户名不能为空！'),
            array('field'=>'usersPassword','name'=>'密码','type'=>1,'prompt'=>'密码不能为空！'),
            array('field'=>'studentCode','name'=>'学籍号','type'=>1,'prompt'=>'学籍号不能为空！'),
            array('field'=>'studentName','name'=>'学生姓名','type'=>1,'prompt'=>'学生姓名不能为空！'),
            array('field'=>'parentsTel','name'=>'家长联系方式','type'=>2,'prompt'=>'家长联系方式不能为空！'),
            array('field'=>'studentAddr','name'=>'家庭住址','type'=>1,'prompt'=>'家庭住址不能为空！')
        );
        $return=$this->validation_field($validation);
        if($return['status']==1)
        {
            $this->msg($return['msg'],1);
            return;
        }
        $key=rand(10000,99999);
        $_POST['usersPassword']=$this->md5ps($_POST['usersPassword'],$key);
        $_POST['usersSecretKey']=$key;
        //录入模型处理
        model('users')->add($_POST);
        $this->msg('添加学生成功！',0);
	}

    //用户修改
    public function edit() {
		/*$this->data=model("admin")->com_list();*/
        $id=$_GET['id'];
        $this->classlist=model('classs')->list_select('');
        $this->grade=model("grade")->list_select('');
        $this->list=model('groupinfo')->admin_list();
        $info=model('users')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑学生';
        $this->action='edit';
        $this->show('users/info');
    }

    //保存用户修改
    public function edit_save() {
        if(!$_POST['usersPassword']){
            unset($_POST['usersPassword']);
        }else{
            $key=rand(10000,99999);
            $_POST['usersPassword']=$this->md5ps($_POST['usersPassword'],$key);
            $_POST['usersSecretKey']=$key;
        }
        /**
         * 1 验证是否为空
         * 2 验证手机号
         * 3 验证邮箱
        */
        $validation=array(
            array('field'=>'gid','name'=>'权限组','type'=>1,'prompt'=>'权限组不能为空！'),
            array('field'=>'usersName','name'=>'用户名','type'=>1,'prompt'=>'用户名不能为空！'),
            array('field'=>'studentCode','name'=>'学籍号','type'=>1,'prompt'=>'学籍号不能为空！'),
            array('field'=>'studentName','name'=>'学生姓名','type'=>1,'prompt'=>'学生姓名不能为空！'),
            array('field'=>'parentsTel','name'=>'家长联系方式','type'=>2,'prompt'=>'家长联系方式不能为空！'),
            array('field'=>'studentAddr','name'=>'家庭住址','type'=>1,'prompt'=>'家庭住址不能为空！')
        );
        $return=$this->validation_field($validation);
        if($return['status']==1)
        {
            $this->msg($return['msg'],1);
            return;
        }
        
        //录入模型处理
        model('users')->edit($_POST);
        $this->msg('修改学生成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('admin')->del($id);
    }

}
