<?php
class rejectedModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function lists($where,$limit)
    {
        $sql='';
        if($where['courseId'])
        {
            $sql.=' and A.courseId='.$where['courseId'];
        }
        if($where['rejectedId'])
        {
            $sql.=' and A.rejectedId='.$where['rejectedId'];
        }
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.rejectedName,B.average,C.className,D.teacherName')
            ->table('rejected_student','A')
            ->add_table('rejected','B','A.rejectedId = B.id')
            ->add_table('class','C','A.classId = C.classId')
            ->add_table('curriculuminfo','E','A.classId = E.classId')
            ->add_table('teacher','D','E.teacherId = D.teacherId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"].$sql)
            ->limit($limit)
            ->select();
        return $data;

    }

    //获取用户列表数目
    public function count($where)
    {
        $sql='';
        if($where['teacherId'])
        {
            $sql.=' and E.teacherId='.$where['teacherId'];
        }
        if($where['rejectedId'])
        {
            $sql.=' and A.rejectedId='.$where['rejectedId'];
        }
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.rejectedName,C.className')
            ->table('rejected_student','A')
            ->add_table('rejected','B','A.rejectedId = B.id')
            ->add_table('class','C','A.classId = C.classId')
            ->add_table('curriculuminfo','E','A.classId = E.classId')
            ->add_table('teacher','D','E.teacherId = D.teacherId')
            ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"].$sql)
            ->count();
        return $num;

    }

    //获取考试内容
    public function info($where)
    {
        return $this->model->table('rejected')->where($where)->find();
    }

    //添加考试内容
    public function add($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['createId']=$_SESSION["user_yg"]["id"];
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $userId=$this->model->table('rejected')->data($data)->insert();
    }

}

?>