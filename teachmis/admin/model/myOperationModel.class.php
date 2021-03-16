<?php
class myOperationModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function paperList($search,$limit)
    {
        $userinfo=$this->model->table('users')->where('usersId='.$_SESSION["user_yg"]["id"])->find();
        $data=$this->model->field('A.*,D.teacherName,E.gradeName')
            ->table('paper','A')
            ->add_table('teacher','D','A.teacherId = D.teacherId')
            ->add_table('grade','E','A.gradeId = E.gradeId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"]." and classId like '%,".$userinfo["classId"].",%'")
            ->limit($limit)
            ->order('A.paperId DESC')
            ->select();
        foreach($data as $vk=>$v)
        {
            $list=$this->model->field('className')->table('class')->where('classId in ('.trim($v["classId"],',').')')->select();
            $str="";
            foreach($list as $lk=>$l)
            {
                if($lk==0)
                {
                    $str=$l["className"];
                }else{
                    $str.=",".$l["className"];
                }
            }
            $data[$vk]["className"]=$str;
            $info=$this->model->table('myOperation')->where('paperId='.$v["paperId"].' and usersId='.$_SESSION["user_yg"]["id"])->select();
            if($info)
            {
                $data[$vk]["status"]=1;
                $data[$vk]["myOperationScore"]=$info[0]["myOperationScore"];
            }else{
                $data[$vk]["status"]=0;
                $data[$vk]["myOperationScore"]='未考试';
            }
        }
        return $data;

    }

    //获取用户列表数目
    public function paperCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename')
            ->table('paper','A')
            ->add_table('admininfo','B','A.schoolId = B.id')
            ->add_table('teacher','D','A.teacherId = D.teacherId')
            ->add_table('grade','E','A.gradeId = E.gradeId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
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


    //获取用户列表
    public function examList($search,$limit)
    {
        $data=$this->model->field('A.*,D.teacherName,E.gradeName')
            ->table('paper','A')
            ->add_table('teacher','D','A.teacherId = D.teacherId')
            ->add_table('grade','E','A.gradeId = E.gradeId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"]." and A.teacherId=".$_SESSION["user_yg"]["id"])
            ->limit($limit)
            ->select();
        foreach($data as $vk=>$v)
        {
            $list=$this->model->field('className')->table('class')->where('classId in ('.trim($v["classId"],',').')')->select();
            $str="";
            foreach($list as $lk=>$l)
            {
                if($lk==0)
                {
                    $str=$l["className"];
                }else{
                    $str.=",".$l["className"];
                }
            }
            $data[$vk]["className"]=$str;
        }
        return $data;

    }

    //获取用户列表数目
    public function examCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename')
            ->table('paper','A')
            ->add_table('admininfo','B','A.schoolId = B.id')
            ->add_table('teacher','D','A.teacherId = D.teacherId')
            ->add_table('grade','E','A.gradeId = E.gradeId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"]." and A.teacherId=".$_SESSION["user_yg"]["id"])
            ->count();
        return $num;

    }

    /**
     * 查询试卷考试信息学生数
     */
    public function examUserCount($id)
    {
        $num=$this->model->field('A.usersId')
            ->table('myOperation','A')
            ->add_table('users','B','A.usersId = B.usersId')
            ->where("A.paperId=".$id)
            ->count();
        return $num;
    }

    /**
     * 查询试卷考试信息
    */
    public function examUserInfo($id,$limit)
    {
        $data=$this->model->field('A.paperId,A.usersId,A.answerId,A.myOperationScore,A.createTime,A.timeLength,B.studentName')
            ->table('myOperation','A')
            ->add_table('users','B','A.usersId = B.usersId')
            ->where("A.paperId=".$id)
            ->limit($limit)
            ->select();
        return $data;
    }

}

?>