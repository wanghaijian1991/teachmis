<?php
//用户管理
class adminMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('admin')->admin_count($_GET['search']);
		//获取信息列表
        $list=model('admin')->admin_list($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
		/*$this->data=model("admin")->info($limit);*/
        $this->list=model('groupinfo')->admin_list();
		$this->action_name='添加';
        $this->action='add';
        $this->show('admin/info');
	}

	public function add_save() {
        if(!$_POST['password']){
            $this->msg('密码不能为空！',0);
            return;
        }
        $_POST['password']=md5($_POST['password']);
        //录入模型处理
        model('admin')->add($_POST);
		header('Location: '.__APP__.'/admin/index');
	}

    //用户修改
    public function edit() {
		/*$this->data=model("admin")->com_list();*/
        $id=$_GET['id'];
        $this->list=model('groupinfo')->admin_list();
        $info=model('admin')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑';
        $this->action='edit';
        $this->show('admin/info');
    }

    //保存用户修改
    public function edit_save() {

        if (!empty($_POST['password']))
        {
            $_POST['password']=md5($_POST['password']);
        }else{
            unset($_POST['password']);
        }
        
        //录入模型处理
        model('admin')->edit($_POST);
		header('Location: '.__APP__.'/admin/index');
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('admin')->del($id);
    }

}