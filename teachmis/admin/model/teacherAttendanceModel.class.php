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
        $data = $this->model->field('A.*,B.teacherName')
            ->table('teacherAskLeave', 'A')
            ->add_table('teacher', 'B','A.teacherId=B.teacherId')
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
            ->table('teacherAskLeave', 'A')
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
        $data['teacherId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('teacherAskLeave')->data($data)->insert();
        return $id;
    }

    //查询列表
    public function list_select($where)
    {
        $data=$this->model->table('teacherAskLeave')->where($where)->where("schoolId=".$_SESSION["user_yg"]["schoolId"])->select();
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
        $data=$this->model->table('teacherAskLeave')->where("schoolId=".$_SESSION["user_yg"]["schoolId"].$where)->find();
        return $data;
    }

    //编辑
    public function edit($data)
    {
        $data['editTime']=date("Y-m-d H:i:s");
        $id=$this->model->table('teacherAskLeave')->data($data)->where("id=".$data["id"])->update();
        return $id;
    }
}
?>