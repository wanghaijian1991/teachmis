<?php
class curriculumModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取列表
    public function curriculumList($search, $limit)
    {
        $data = $this->model->field('A.*,C.className,D.gradeName,E.teacherName')
            ->table('curriculum', 'A')
            ->add_table('curriculuminfo', 'B', 'A.curriculumId = B.curriculumId')
            ->add_table('class', 'C', 'A.classId = C.classId')
            ->add_table('grade', 'D', 'C.gradeId = D.gradeId')
            ->add_table('teacher', 'E', 'C.teacherId = E.teacherId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->limit($limit)
            ->select();
        return $data;

    }

    //获取总数
    public function curriculumCount($search)
    {
        $num = $this->model->field('A.*,C.className,D.gradeName')
            ->table('curriculum', 'A')
            ->add_table('curriculuminfo', 'B', 'A.curriculumId = B.curriculumId')
            ->add_table('class', 'C', 'A.classId = C.classId')
            ->add_table('grade', 'D', 'C.gradeId = D.gradeId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->count();
        return $num;

    }

    //添加课程表内容
    public function add($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['founderId']=$_SESSION["user_yg"]["id"];
        $data['schoolId']=$_SESSION["user_yg"]["id"];
        $userId=$this->model->table('curriculum')->data($data)->insert();
    }
}
?>