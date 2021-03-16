<?php
//公司部门管理
class companyMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=10;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('company')->com_count();
		//获取信息列表
        $list=model('company')->com_list($limit);
        $this->assign('list',$list);
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
		$this->list=model('company')->com_list($limit);
		$this->action_name='添加';
        $this->action='add_save';
        $this->show('company/addinfo');
	}
	//动态提取下级部门列表
	public function downlist() {
		$list=model("company")->flist(in($_GET["id"]));
	}


	public function add_save() {
		$data=in($_POST);
        //添加考核信息
        model('company')->add($data);
		header('Location: '.__APP__.'/company/index');
	}

    //用户修改
    public function edit() {
        $id=$_GET['id'];
        $this->info=model('company')->info($id);
		$this->list=model('company')->com_list($limit);
        $this->action_name='编辑';
        $this->action='edit_save';
        $this->show('company/addinfo');
    }

    //保存用户修改
    public function edit_save() {
		$data=in($_POST);
		model("company")->update($data);
		header('Location: '.__APP__.'/company/index');
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        model('company')->del($id);
    }

}