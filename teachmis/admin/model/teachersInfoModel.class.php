<?php
class teachersInfoModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function teachersList($search,$limit)
    {
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.nicename')
                ->table('teacher','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->where($search)
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
				->limit($limit)
                ->select();
        $edu[0]="其他";
        $edu[1]="大专";
        $edu[2]="本科";
        $edu[3]="研究生";
        $edu[4]="博士";
        $edu[5]="博士后";
        $sex[0]="男";
        $sex[1]="女";
        if($data)
        foreach($data as $key=>$v)
        {
            $data[$key]["teacherSex"]=$sex[$v["teacherSex"]];
            $data[$key]["teacherEdu"]=$edu[$v["teacherEdu"]];
        }
        return $data;

    }

    //获取用户列表数目
    public function teachrsCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename')
                ->table('teacher','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->where($search)
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('teacher')->where('teacherId='.$id)->find();
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
        $userId=$this->model->table('teacher')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('teacher')->data($data)->where("teacherId=".$data["teacherId"])->update();
        return $id;
    }

    //删除用户内容
    public function del($id)
    {
		$data=$this->model->table('users')->where('teacherId='.$id)->find();
		if($data){
            $this->msg('当前教师存在关联的学生！',1);
            exit;
        }
        $class=$this->model->table('class')->where('teacherId='.$id)->find();
		if($class){
            $this->msg('当前教师存在关联的班级！',1);
            exit;
        }
        $status=$this->model->table('teacher')->where('teacherId='.intval($id))->delete();
        return $status;
    }

    //
    public function list_select($where)
    {
        $data=$this->model->table('teacher')->where($where)->where("schoolId=".$_SESSION["user_yg"]["schoolId"])->select();
        return $data;
    }

}

?>