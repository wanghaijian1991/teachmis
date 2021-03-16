<?php
//用户管理
class examMod extends commonMod {

	public function __construct()
    {
        parent::__construct();
    }

	public function index() {
        $listRows=2;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('myOperation')->examCount($_GET['search']);
        //获取信息列表
        $list=model('myOperation')->examList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->assign('num',ceil($num/$listRows));
        $this->menu_name='试卷列表';
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
        $this->show('paper/info');
	}

	public function add_save() {
        //录入模型处理
        model('myOperation')->add($_POST);
        header("location:".__URL__);
	}

	/**
     * 参与考试人员信息
	*/
	public function examuserinfo()
    {
        $paperId=$_GET['id'];
        $listRows=2;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('myOperation')->examUserCount($paperId);
        //获取信息列表
        $userlist=model('myOperation')->examUserInfo($paperId,$limit);
        $this->assign('list',$userlist);
        $this->assign('num',ceil($num/$listRows));
        $this->menu_name='试卷学生列表';
        $this->page=$this->page($url,$num,$listRows);
        $this->show();
    }

    public function info()
    {
        $status=$this->model->table('myOperation')->where('paperId='.$_GET["id"].' and usersId='.$_GET["usersId"])->find();
        $answer_list=explode(';',$status['answerId']);
        $info=model('paper')->info($_GET["id"]);
        $paperTypeList=explode(";",$info["questionsIds"]);
        $questionsTypes=explode(",",$info["questionsTypes"]);
        $num=0;
        foreach($paperTypeList as $ptlk=>$ptl)
        {
            if($ptl)
            {
                $paperList=explode(",",trim($ptl,','));
                if($questionsTypes[$ptlk]==3)
                {
                    $gapFilling=explode(',',$answer_list[$num]);
                }
                $list=array();
                foreach($paperList as $plk=>$pl)
                {
                    $list[$plk]=model("questions")->info($pl);
                    $answer=$this->model->table('answer')->where('questionsId='.$list[$plk]["questionsId"])->select();
                    if($list[$plk]["questionsType"]<3)
                    {
                        shuffle($answer);
                    }
                    if($questionsTypes[$ptlk]==3)
                    {
                        foreach($answer as $l=>$an)
                        {
                            $answer[$l]['answer_z']=$gapFilling[$l];
                        }
                    }else{
                        $list[$plk]['answer_z']=explode(',',$answer_list[$num]);
                    }
                    $list[$plk]["answer"]=$answer;
                }
                $num++;
                if($questionsTypes[$ptlk]==1) $names='单选题';
                if($questionsTypes[$ptlk]==2) $names='多选题';
                if($questionsTypes[$ptlk]==3) $names='填空题';
                if($questionsTypes[$ptlk]==4) $names='写作题';
                $questionsList[$ptlk]["name"]=$names;
                $questionsList[$ptlk]["list"]=$list;
            }
        }
        $answer[0]="A";
        $answer[1]="B";
        $answer[2]="C";
        $answer[3]="D";
        $answer[4]="E";
        $answer[5]="F";
        $answer[6]="G";
        $this->assign("questionsList",$questionsList);
        $this->assign("answer",$answer);
        $this->assign("info",$info);
        $this->action_name='考试';
        $this->action='add';
        $this->show();
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
        $this->show('paper/edit');
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

    function ajaxPaperList()
    {
        $listRows=2;//每页显示的信息条数
        $limit=($_POST["page"]-1)*$listRows.",".$listRows;

        //获取行数
        $num=model('myOperation')->paperCount($_GET['search']);
        //获取信息列表
        $list=model('myOperation')->paperList($_GET['search'],$limit);
        $data["page"]=ceil($num/$listRows);
        $data["list"]=$list;
        echo json_encode($data);
    }

    function reference(){}

    function notreference(){}

}