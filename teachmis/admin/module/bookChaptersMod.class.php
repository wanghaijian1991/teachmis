<?php
//用户管理
class bookChaptersMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        /*$list=model('bookChapters')->bookChaptersList("A.bookChaptersFid=0");*/
        $this->grade=model("grade")->list_select('');
        /*$this->assign('list',$list);*/
        $this->menu_name='章节列表';
		$this->show();
	}

	//用户添加
	public function add() {
	    if($_GET["id"])
	    {
            $bookChapterslist=model('bookChapters')->list_select('bookChaptersId='.$_GET["id"]);
            $info["gradeId"]=$bookChapterslist[0]["gradeId"];
	        $info["bookChaptersFid"]=$_GET["id"];
            $this->assign("info",$info);
        }else{
            $this->bookChaptersFid=0;
        }
        $this->grade=model("grade")->list_select('');
		$this->action_name='添加章节';
        $this->action='add';
        $this->show('bookChapters/info');
	}

	public function add_save() {
        //录入模型处理
        model('bookChapters')->add($_POST);
        $this->msg('添加章节成功！',0);
	}

    //用户修改
    public function edit() {
		/*$this->data=model("admin")->com_list();*/
        $id=$_GET['id'];
        $this->grade=model("grade")->list_select('');
        $info=model('bookChapters')->info($id);
		$this->assign("info",$info);
        $this->action_name='编辑章节';
        $this->action='edit';
        $this->show('bookChapters/info');
    }

    //保存用户修改
    public function edit_save() {
        //录入模型处理
        model('bookChapters')->edit($_POST);
        $this->msg('修改章节成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('admin')->del($id);
    }

    function ajaxChapters()
    {
        $bookChaptersId=$_POST["bookChaptersId"];
        $gradeId=$_POST["gradeId"];
        $list=model('bookChapters')->bookChaptersList("A.bookChaptersFid=".$bookChaptersId." and gradeId=".$gradeId);
        echo json_encode($list);
    }

}