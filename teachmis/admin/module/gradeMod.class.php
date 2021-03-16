<?php
class gradeMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('grade')->gradeCount($_GET['search']);
		//获取信息列表
        $list=model('grade')->gradeList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='年级列表';
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
		$this->action_name='添加年级';
        $this->action='add';
        $this->show('grade/info');
	}

	public function add_save() {
        //录入模型处理
        model('grade')->add($_POST);
        $this->msg('添加年级成功！',0);
	}

    //用户修改
    public function edit() {
		/*$this->data=model("admin")->com_list();*/
        $id=$_GET['id'];
        $info=model('grade')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑年级';
        $this->action='edit';
        $this->show('grade/info');
    }

    //保存用户修改
    public function edit_save() {
        //录入模型处理
        model('grade')->edit($_POST);
        $this->msg('修改年级成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('admin')->del($id);
    }

}