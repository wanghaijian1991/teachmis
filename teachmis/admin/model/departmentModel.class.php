<?php
class departmentModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取列表
    public function departmentList($search, $limit)
    {
        $data = $this->model->field('A.*,B.teacherName')
            ->table('department', 'A')
            ->add_table('teacher', 'B', 'A.teacherId = B.teacherId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->limit($limit)
            ->select();
        foreach($data as &$l)
        {
            $department=$this->model->table('department')->where('id='.$l['fid'])->find();
            $l['fidName']=$department['departmentName'];
        }
        return $data;

    }

    //获取总数
    public function departmentCount($search)
    {
        $num = $this->model->field('A.*,B.teacherName')
            ->table('department', 'A')
            ->add_table('teacher', 'B', 'A.teacherId = B.teacherId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->count();
        return $num;

    }

    //添加课程表内容
    public function add($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $curriculumId=$this->model->table('department')->data($data)->insert();
        return $curriculumId;
    }

    //添加课程表详情内容
    public function addInfo($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $curriculumId=$this->model->table('department')->data($data)->insert();
        return $curriculumId;
    }

    //查询列表
    public function list_select($where)
    {
        $data=$this->model->table('department')->where($where)->where("schoolId=".$_SESSION["user_yg"]["schoolId"])->select();
        return $data;
    }

    //查询列表
    public function info($id)
    {
        $data=$this->model->table('department')->where('id='.$id." and schoolId=".$_SESSION["user_yg"]["schoolId"])->find();
        return $data;
    }
}
?>