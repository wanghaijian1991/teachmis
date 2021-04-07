<?php
class usersModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function usersList($where,$limit)
    {
        $sql='';
        if($where['teacherId'])
        {
            $sql.=' and E.teacherId='.$where['teacherId'];
        }
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.nicename,C.className,D.teacherName')
                ->table('users','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->add_table('class','C','A.classId = C.classId')
                ->add_table('teacher','D','C.teacherId = D.teacherId')
                ->add_table('curriculuminfo','E','A.classId = E.classId')
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"].$sql)
				->limit($limit)
                ->select();
        $sex[0]="男";
        $sex[1]="女";
        if($data)
            foreach($data as $key=>$v)
            {
                $data[$key]["studentSex"]=$sex[$v["studentSex"]];
            }
        return $data;

    }

    //获取用户列表数目
    public function usersCount($where)
    {
        $sql='';
        if($where['teacherId'])
        {
            $sql.=' and E.teacherId='.$where['teacherId'];
        }
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.nicename,C.className')
                ->table('users','A')
                ->add_table('admininfo','B','A.schoolId = B.id')
                ->add_table('class','C','A.classId = C.classId')
                ->add_table('teacher','D','C.teacherId = D.teacherId')
                ->add_table('curriculuminfo','E','A.classId = E.classId')
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"].$sql)
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('users')->where('usersId='.$id)->find();
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
        $data['founderId']=$_SESSION["user_yg"]["id"];
        $data['schoolId']=$_SESSION["user_yg"]["id"];
        $userId=$this->model->table('users')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('users')->data($data)->where("usersId=".$data["usersId"])->update();
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
        $data=$this->model->table('admininfo')->where($where)->select();
        return $data;
    }

}

?>