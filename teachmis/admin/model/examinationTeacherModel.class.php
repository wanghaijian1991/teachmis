<?php
class examinationTeacherModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取列表
    public function infoList($search, $limit)
    {
        $data = $this->model->field('A.*,B.askLeaveType,B.askLeaveTimeStart,B.askLeaveTimeEnd,B.askLeaveCause,B.askLeaveStatus,C.teacherName,D.teacherName as askLeaveAgent')
            ->table('examinationTeacher', 'A')
            ->add_table('teacherAskLeave', 'B','A.applyId=B.id')
            ->add_table('teacher', 'C','B.teacherId=C.teacherId')
            ->add_table('teacher', 'D','B.askLeaveAgent=D.teacherId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"]." and A.status=1 and A.teacherId=".$_SESSION["user_yg"]["id"]." and A.status>-2")
            ->limit($limit)
            ->select();
        return $data;

    }

    //获取总数
    public function infoCount($search)
    {
        $num = $this->model->field('A.*,B.askLeaveType,B.askLeaveTimeStart,B.askLeaveTimeEnd,B.askLeaveCause,B.askLeaveAgent,B.askLeaveStatus,C.teacherName')
            ->table('examinationTeacher', 'A')
            ->add_table('teacherAskLeave', 'B','A.applyId=B.id')
            ->add_table('teacher', 'C','B.teacherId=C.teacherId')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"]." and A.status=1 and A.teacherId=".$_SESSION["user_yg"]["id"]." and A.status>-2")
            ->count();
        return $num;

    }

    //添加
    public function addinfo($data)
    {
        return $this->model->table('examinationTeacher')->data($data)->insert();
    }

    //查询
    public function info($data)
    {
        $where="";
        foreach($data as $k=>$v)
        {
            $where.=" and ".$v;
        }
        $data=$this->model->table('examinationTeacher')->where("schoolId=".$_SESSION["user_yg"]["schoolId"].$where)->find();
        return $data;
    }

    //编辑
    public function edit($data,$type=0)
    {
        $data['editTime']=date("Y-m-d H:i:s");
        if($type==0)
        {
            $id=$this->model->table('examinationTeacher')->data($data)->where("id=".$data["id"])->update();
        }else{
            $id=$data["id"];
            unset($data["id"]);
            $id=$this->model->table('examinationTeacher')->data($data)->where("id in(".$id.")")->update();
        }
        return $id;
    }

    //编辑
    public function update($data,$where)
    {
        $data['editTime']=date("Y-m-d H:i:s");
        $id=$this->model->table('examinationTeacher')->data($data)->where($where)->update();
        return $id;
    }
}
?>