<?php
class questionsModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function questionsList($search,$limit)
    {
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.nicename,C.gradeName')
                ->table('questions','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->add_table('grade','C','A.gradeId = C.gradeId')
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
				->limit($limit)
                ->select();
        $difficulty[1]="一星";
        $difficulty[2]="两星";
        $difficulty[3]="三星";
        $difficulty[4]="四星";
        $difficulty[5]="五星";
        $questionsType[1]="单选题";
        $questionsType[2]="多选题";
        $questionsType[3]="填空题";
        $questionsType[4]="写作题";
        if($data)
            foreach($data as $key=>$v)
            {
                $data[$key]["questionsType"]=$questionsType[$v["questionsType"]];
                if($v["bookChaptersId"])
                {
                    $data[$key]["bookChaptersName"]=model('bookChapters')->getbookChapters($v["bookChaptersId"]);
                }else{
                    $data[$key]["bookChaptersName"]='';
                }
                $data[$key]["questionsDifficulty"]=$difficulty[$v["questionsDifficulty"]];
            }
        return $data;

    }

    //获取用户列表数目
    public function questionsCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename,C.gradeName')
                ->table('questions','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->add_table('grade','C','A.gradeId = C.gradeId')
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('questions')->where('questionsId='.$id)->find();
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
        $data['createTime']=date("Y-m-d H:i:s");
        $data['createId']=$_SESSION["user_yg"]["id"];
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $questionsId=$this->model->table('questions')->data($data)->insert();
        if($data["answerstr"]){
            $answerArr=explode(";",$data["answerstr"]);
            $answerIdStr=",";
            foreach($answerArr as $v)
            {
                if($v)
                {
                    $arr=explode(",",$v);
                    $answerData["questionsId"]=$questionsId;
                    $answerData["answerName"]=$arr[0];
                    if($arr[1]==2)
                    {
                        $answerData["answerStatus"]=0;
                        $this->model->table('answer')->data($answerData)->insert();
                    }else{
                        $answerData["answerStatus"]=1;
                        $answerId=$this->model->table('answer')->data($answerData)->insert();
                        $answerIdStr.=$answerId.",";
                    }
                }
            }
            $updateData["questionsAnswer"]=$answerIdStr;
            $this->model->table('questions')->data($updateData)->where("questionsId=".$questionsId)->update();
        }
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