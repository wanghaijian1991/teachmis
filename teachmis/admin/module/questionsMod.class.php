<?php
//用户管理
class questionsMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=20;//每页显示的信息条数
		$url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);
		
        //获取行数
		$num=model('questions')->questionsCount($_GET['search']);
		//获取信息列表
        $list=model('questions')->questionsList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='试题列表';
        $this->page=$this->page($url,$num,$listRows);
		$this->show();
	}

	//用户添加
	public function add() {
        $this->grade=model('grade')->list_select('');
        $this->bookChapters=model('bookChapters')->bookChaptersList("A.bookChaptersFid=0");
        $difficulty[1]="一星";
        $difficulty[2]="两星";
        $difficulty[3]="三星";
        $difficulty[4]="四星";
        $difficulty[5]="五星";
        $questionsType[1]="单选题";
        $questionsType[2]="多选题";
        $questionsType[3]="填空题";
        $questionsType[4]="写作题";
        $this->assign('difficulty',$difficulty);
        $this->assign('questionsType',$questionsType);
		$this->action_name='添加试题';
        $this->action='add';
        $this->show('questions/info');
	}

	public function add_save() {
        //录入模型处理
        model('questions')->add($_POST);
        $this->msg('添加试题成功！',0);
	}

    //用户修改
    public function edit() {
		/*$this->data=model("admin")->com_list();*/
        $id=$_GET['id'];
        $this->grade=model('grade')->list_select('');
        $this->bookChapters=model('bookChapters')->list_select('');
        $difficulty[1]="一星";
        $difficulty[2]="两星";
        $difficulty[3]="三星";
        $difficulty[4]="四星";
        $difficulty[5]="五星";
        $questionsType[1]="单选题";
        $questionsType[2]="多选题";
        $questionsType[3]="填空题";
        $questionsType[4]="写作题";
        $this->assign('difficulty',$difficulty);
        $this->assign('questionsType',$questionsType);
        $info=model('questions')->info($id);
        $bookChaptersId=trim($info["bookChaptersId"],",");
        $bookChaptersIdArr=explode(",",$bookChaptersId);
        $bookChaptersArr=array();
        foreach($bookChaptersIdArr as $key=>$b)
        {
            if($key==0)
            {
                $bookChaptersArr[$key]=model('bookChapters')->list_select('bookChaptersFid=0');
            }else{
                $keys=$key-1;
                $bookChaptersArr[$key]=model('bookChapters')->list_select('bookChaptersFid='.$bookChaptersIdArr[$keys]);
            }
        }
        $answerList=model('questions')->getAnswerList($id);
        $this->assign("bookChaptersArr",$bookChaptersArr);
        $this->assign("bookChaptersIdArr",$bookChaptersIdArr);
        $this->assign("bookChaptersNum",count($bookChaptersIdArr)-1);
        $this->assign("answerList",$answerList);
        $this->assign("answerNum",count($answerList)-1);
		$this->assign("info",$info);
        $this->action_name='编辑试题';
        $this->action='edit';
        $this->show('questions/edit');
    }

    //保存用户修改
    public function edit_save() {
        //录入模型处理
        model('questions')->edit($_POST);
        $this->msg('修改试题成功！',0);
    }

    //用户删除
    public function del() {
        $id=intval($_POST['id']);
        //录入模型处理
        model('questions')->del($id);
        $this->msg('删除试题成功！',0);
    }

}