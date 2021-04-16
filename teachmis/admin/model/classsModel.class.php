<?php
class classsModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function classsList($search,$limit)
    {
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.nicename,C.teacherName,D.gradeName')
            ->table('class','A')
            ->add_table('admininfo','B','A.schoolId = B.id')
            ->add_table('teacher','C','A.teacherId = C.teacherId')
            ->add_table('grade','D','A.gradeId = D.gradeId')
            ->where($search)
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
            ->limit($limit)
            ->select();
        return $data;

    }

    //获取用户列表数目
    public function classsCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename,C.teacherName,D.gradeName')
            ->table('class','A')
            ->add_table('admininfo','B','A.schoolId = B.id')
            ->add_table('teacher','C','A.teacherId = C.teacherId')
            ->add_table('grade','D','A.gradeId = D.gradeId')
            ->where($search)
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
            ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('class')->where('classId='.$id)->find();
    }

    //获取用户内容
    public function searchInfo($where)
    {
        return $this->model->table('class')->where($where)->find();
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
        $userId=$this->model->table('class')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('class')->data($data)->where("classId=".$data["classId"])->update();
        return $id;
    }

    //删除用户内容
    public function del($id)
    {
		$data=$this->model->table('admininfo')->where('id='.$id)->find();
		$this->model->table('department')->where('id='.$data["did"])->delete();
        return $this->model->table('admininfo')->where('id='.intval($id))->delete(); 
    }

    //
    public function list_select($where)
    {
        if($where)
        {
            $where="schoolId=".$_SESSION["user_yg"]["schoolId"]." and ".$where;
        }else{
            $where="schoolId=".$_SESSION["user_yg"]["schoolId"];
        }
        $data=$this->model->table('class')->where($where)->select();
        return $data;
    }

}

?>