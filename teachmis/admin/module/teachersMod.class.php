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
        $validation=array(
            array('field'=>'gid','name'=>'权限组','type'=>1,'prompt'=>'权限组不能为空！'),
            array('field'=>'teacherName','name'=>'教师姓名','type'=>1,'prompt'=>'教师姓名不能为空！'),
            array('field'=>'teacherPassword','name'=>'密码','type'=>1,'prompt'=>'密码不能为空！'),
            array('field'=>'teacherPhone','name'=>'联系方式','type'=>2,'prompt'=>'联系方式不能为空！'),
            array('field'=>'graduationSchool','name'=>'毕业院校','type'=>1,'prompt'=>'毕业院校不能为空！'),
            array('field'=>'teacherEdu','name'=>'学历','type'=>1,'prompt'=>'学历不能为空！'),
            array('field'=>'teacherEmergencyPhone','name'=>'应急联系方式','type'=>2,'prompt'=>'应急联系方式不能为空！'),
            array('field'=>'teacherAddress','name'=>'教师住址','type'=>1,'prompt'=>'教师住址不能为空！')
        );
        $return=$this->validation_field($validation);
        if($return['status']==1)
        {
            $this->msg($return['msg'],1);
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
        $validation=array(
            array('field'=>'gid','name'=>'权限组','type'=>1,'prompt'=>'权限组不能为空！'),
            array('field'=>'teacherName','name'=>'教师姓名','type'=>1,'prompt'=>'教师姓名不能为空！'),
            array('field'=>'teacherPhone','name'=>'联系方式','type'=>2,'prompt'=>'联系方式不能为空！'),
            array('field'=>'graduationSchool','name'=>'毕业院校','type'=>1,'prompt'=>'毕业院校不能为空！'),
            array('field'=>'teacherEdu','name'=>'学历','type'=>1,'prompt'=>'学历不能为空！'),
            array('field'=>'teacherEmergencyPhone','name'=>'应急联系方式','type'=>2,'prompt'=>'应急联系方式不能为空！'),
            array('field'=>'teacherAddress','name'=>'教师住址','type'=>1,'prompt'=>'教师住址不能为空！')
        );
        $return=$this->validation_field($validation);
        if($return['status']==1)
        {
            $this->msg($return['msg'],1);
            return;
        }
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