<?php
class teacherAttendanceModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取列表
    public function infoList($search, $limit)
    {
        $data = $this->model->field('A.*')
            ->table('teacherAttendance', 'A')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->limit($limit)
            ->select();
        return $data;

    }

    //获取总数
    public function infoCount($search)
    {
        $num = $this->model->field('A.*')
            ->table('teacherAttendance', 'A')
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

    //查询列表
    public function list_select($where)
    {
        $data=$this->model->table('department')->where($where)->where("schoolId=".$_SESSION["user_yg"]["schoolId"])->select();
        return $data;
    }

    //查询列表
    public function info($data)
    {
        $where="";
        foreach($data as $k=>$v)
        {
            $where.=" and ".$v;
        }
        $data=$this->model->table('department')->where("schoolId=".$_SESSION["user_yg"]["schoolId"].$where)->find();
        return $data;
    }
}
?>