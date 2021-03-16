<?php
class myPractiseModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function myPractiseList($search,$limit)
    {
        /*$userinfo=$this->model->table('users')->where('usersId='.$_SESSION["user_yg"]["id"])->find();
        $data=$this->model->field('A.*,D.teacherName,E.gradeName')
            ->table('paper','A')
            ->add_table('teacher','D','A.teacherId = D.teacherId')
            ->add_table('grade','E','A.gradeId = E.gradeId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"]." and classId like '%,".$userinfo["classId"].",%'")
            ->limit($limit)
            ->select();*/
        $data=$this->model
            ->field("A.userQuestionId,A.status,A.createTime,B.questionsName,B.questionsType,B.questionsDifficulty")
            ->table('userQuestion','A')
            ->add_table('questions','B','A.questionsId = B.questionsId')
            ->where("usersId=".$_SESSION["user_yg"]["id"])
            ->limit($limit)
            ->select();
        foreach($data as $key=>$v)
        {
            $difficulty[1]="一星";
            $difficulty[2]="两星";
            $difficulty[3]="三星";
            $difficulty[4]="四星";
            $difficulty[5]="五星";
            $questionsType[1]="单选题";
            $questionsType[2]="多选题";
            $questionsType[3]="填空题";
            $questionsType[4]="写作题";
            $data[$key]["questionsType"]=$questionsType[$v["questionsType"]];
            $data[$key]["questionsDifficulty"]=$difficulty[$v["questionsDifficulty"]];
        }
        return $data;

    }

    //获取用户列表数目
    public function myPractiseCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model
            ->field("A.userQuestionId,A.status,B.questionsName,B.questionsType,B.questionsDifficulty")
            ->table('userQuestion','A')
            ->add_table('questions','B','A.questionsId = B.questionsId')
            ->where("usersId=".$_SESSION["user_yg"]["id"])
            ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('paper')->where('paperId='.$id)->find();
    }

    //检测重复用户
    public function count($user,$id=null)
    {
        if(!empty($id)){
            $where=' AND id<>'.$id;
        }
        return $this->model->table('admininfo')->where('adminname="'.$user.'"'.$where)->count(); 
    }

    //添加用户内容
    public function add($data)
    {
        $info=model('paper')->info($data["paperId"]);
        $paperTypeList=explode(";",$info["questionsIds"]);
        $questionsTypes=explode(",",$info["questionsTypes"]);
        $score=0;
        $answerStr='';
        foreach($paperTypeList as $ptlk=>$ptl)
        {
            if($ptl)
            {
                $paperList=explode(",",trim($ptl,','));
                $questionsinfo=array();
                foreach($paperList as $plk=>$pl)
                {
                    $userQuestion=array();
                    $questionsinfo=model("questions")->info($pl);
                    if($questionsinfo["questionsType"]==1)
                    {
                        $answer=$this->model->field('answerId')->table('answer')->where('questionsId='.$questionsinfo["questionsId"].' and answerStatus=1')->select();
                        if($answer[0]["answerId"]==$data["questionsKey".$ptlk."_".$plk])
                        {
                            $score++;
                            $userQuestion["status"]=1;
                        }else{
                            $userQuestion["status"]=0;
                            $this->model->query("update att_questions set errorNum=errorNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        }
                        $this->model->query("update att_questions set questionsSelectNum=questionsSelectNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        $answerStr=$answerStr.$data["questionsKey".$ptlk."_".$plk].";";
                    }
                    if($questionsinfo["questionsType"]==2)
                    {
                        $answer=$this->model->field('answerId')->table('answer')->where('questionsId='.$questionsinfo["questionsId"].' and answerStatus=1')->select();
                        $qstatus=1;
                        if(count($answer)==count($data["questionsKey".$ptlk."_".$plk]))
                        {
                            foreach($data["questionsKey".$ptlk."_".$plk] as $anv)
                            {
                                if(!in_array($anv,$answer))
                                {
                                    $qstatus=0;
                                }
                            }
                        }else{
                            $qstatus=0;
                        }
                        if($qstatus==1)
                        {
                            $score++;
                            $userQuestion["status"]=1;
                        }else{
                            $userQuestion["status"]=0;
                            $this->model->query("update att_questions set errorNum=errorNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        }
                        $this->model->query("update att_questions set questionsSelectNum=questionsSelectNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        $answerStr=$answerStr.implode(',',$data["questionsKey".$ptlk."_".$plk]).";";
                    }
                    if($questionsinfo["questionsType"]==3)
                    {
                        $answer=$this->model->field('answerName')->table('answer')->where('questionsId='.$questionsinfo["questionsId"].' and answerStatus=1')->select();
                        $qstatus=1;
                        if(count($answer)==count($data["questionsKey".$ptlk."_".$plk]))
                        {
                            foreach($data["questionsKey".$ptlk."_".$plk] as $anv)
                            {
                                if(!in_array($anv,$answer))
                                {
                                    $qstatus=0;
                                }
                            }
                        }else{
                            $qstatus=0;
                        }
                        if($qstatus==1)
                        {
                            $score++;
                            $userQuestion["status"]=1;
                        }else{
                            $userQuestion["status"]=0;
                            $this->model->query("update att_questions set errorNum=errorNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        }
                        $this->model->query("update att_questions set questionsSelectNum=questionsSelectNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        $answerStr=$answerStr.implode(',',$data["questionsKey".$ptlk."_".$plk]).";";
                    }
                    if($questionsinfo["questionsType"]==4)
                    {
                        $this->model->query("update att_questions set questionsSelectNum=questionsSelectNum+1 where questionsId=".$questionsinfo["questionsId"]);
                        $answerStr=$answerStr.$data["questionsKey".$ptlk."_".$plk];
                    }
                    $userQuestion["questionsId"]=$questionsinfo["questionsId"];
                    $userQuestion["usersId"]=$_SESSION["user_yg"]["id"];
                    $userQuestion['createTime']=date("Y-m-d H:i:s");
                    $this->model->table('userQuestion')->data($userQuestion)->insert();
                }
            }
        }
        $data['answerId']=$answerStr;
        $data['usersId']=$_SESSION["user_yg"]["id"];
        $data['myOperationScore']=$score;
        $data['createTime']=date("Y-m-d H:i:s");
        $questionsId=$this->model->table('myOperation')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('questions')->data($data)->where("questionsId=".$data["questionsId"])->update();
        if($data["answerstr"]){
            $answerArr=explode(";",$data["answerstr"]);
            $answerIdStr=",";
            $answerstr="";
            foreach($answerArr as $v)
            {
                if($v)
                {
                    $arr=explode(",",$v);
                    $answerData["questionsId"]=$data["questionsId"];
                    $answerData["answerName"]=$arr[0];
                    if($arr[2])
                    {
                        if($arr[1]==2)
                        {
                            $answerData["answerStatus"]=0;
                            $this->model->table('answer')->data($answerData)->where("answerId=".intval($arr[2]))->update();
                        }else{
                            $answerData["answerStatus"]=1;
                            $answerId=$this->model->table('answer')->data($answerData)->where("answerId=".intval($arr[2]))->update();
                            $answerIdStr.=$answerId.",";
                        }
                        $answerstr.=$arr[2].",";
                    }else{
                        if($arr[1]==2)
                        {
                            $answerData["answerStatus"]=0;
                            $answerId=$this->model->table('answer')->data($answerData)->insert();
                            $answerstr.=$answerId.",";
                        }else{
                            $answerData["answerStatus"]=1;
                            $answerId=$this->model->table('answer')->data($answerData)->insert();
                            $answerstr.=$answerId.",";
                            $answerIdStr.=$answerId.",";
                        }
                    }
                }
            }
            $this->model->table('answer')->where('questionsId='.intval($data["questionsId"]).' and answerId not in('.trim($answerstr,",").')')->delete();
            $updateData["questionsAnswer"]=$answerIdStr;
            $this->model->table('questions')->data($updateData)->where("questionsId=".intval($data["questionsId"]))->update();
        }
        return $id;
    }

    //删除用户内容
    public function del($id)
    {
        $this->model->table('answer')->where('questionsId='.intval($id))->delete();
        return $this->model->table('questions')->where('questionsId='.intval($id))->delete();
    }

    //
    public function list_select($where)
    {
        $data=$this->model->table('questions')->where($where)->select();
        return $data;
    }

    public function getAnswerList($id)
    {
        $data=$this->model->table('answer')->where("questionsId=".$id)->select();
        return $data;
    }

}

?>