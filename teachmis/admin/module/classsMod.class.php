<?php
//班级管理
class classsMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('classs')->classsCount($_GET['search']);
		//获取信息列表
        $list=model('classs')->classsList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='班级列表';
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
		$this->teachers=model("teachers")->list_select('');
        $this->grade=model("grade")->list_select('');
		$this->action_name='添加班级';
        $this->action='add';
        $this->show('classs/info');
	}

	public function add_save() {
        //录入模型处理
        model('classs')->add($_POST);
        $this->msg('添加班级成功！',0);
	}

    //用户修改
    public function edit() {
        $id=$_GET['id'];
        $this->teachers=model("teachers")->list_select('');
        $this->grade=model("grade")->list_select('');
        $info=model('classs')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑班级';
        $this->action='edit';
        $this->show('classs/info');
    }

    //保存用户修改
    public function edit_save() {
        //录入模型处理
        model('classs')->edit($_POST);
        $this->msg('修改班级成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('admin')->del($id);
    }

    public function ajaxClassList()
    {
        $gradeId=$_POST["gradeId"];
        if($gradeId)
        {
            $where='gradeId='.$gradeId;
            $list=model('classs')->list_select($where);
            echo json_encode($list);
        }
    }

}