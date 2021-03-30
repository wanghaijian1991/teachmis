<?php
class examinationTeacherModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //添加
    public function add($data)
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
}
?>