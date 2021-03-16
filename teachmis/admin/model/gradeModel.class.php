<?php
class gradeModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取用户列表
    public function gradeList($search,$limit)
    {
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*')
                ->table('grade','A')
                ->where($search)
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
				->limit($limit)
                ->select();
        return $data;

    }

    //获取用户列表数目
    public function gradeCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*')
                ->table('grade','A')
                ->where($search)
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('grade')->where('gradeId='.$id)->find();
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
        $userId=$this->model->table('grade')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('grade')->data($data)->where("gradeId=".$data["gradeId"])->update();
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
        $data=$this->model->table('grade')->where("schoolId=".$_SESSION["user_yg"]["schoolId"])->select();
        return $data;
    }

}

?>