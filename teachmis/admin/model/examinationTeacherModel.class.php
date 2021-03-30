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
}
?>