<?php
class adminModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //当前用户信息
    public function current_user() {
        $uid=cp_decode($_SESSION[$this->config['SPOT'].'_user'],$this->config['DB_PREFIX']);
        if(empty($uid)){
            return ;
        }
        if($_SESSION["user_yg"]["userType"]==2)
        {
            $user=$this->model->table('users')->where('usersId='.$uid)->find();
        }else if($_SESSION["user_yg"]["userType"]==1)
        {
            $user=$this->model->table('teacher')->where('teacherId='.$uid)->find();
        }else{
            $user=$this->model->table('admininfo')->where('id='.$uid)->find();
        }
        $user_group=$this->model->table('groupinfo')->where('id='.$user['gid'])->find();
        $user['gname']=$user_group['name'];
        if($user['id']!=1)
        $user['model_power']=$user_group['grouppower'];
        $user['class_power']='';
        $user['status_power']=0;
        $user['grade']=$user_group['grouppower'];
        $user['keep']=$user_group['keep'];
        return $user;
    }

    //获取用户列表
    public function admin_list($search,$limit)
    {
        /*$user=$this->current_user();*/
        $data=$this->model->field('A.*,B.groupname')
                ->table('admininfo','A')
                ->add_table('groupinfo','B','A.gid = B.id')
                ->where('A.adminname like "%'.$search.'%"')
				->limit($limit)
                ->select();
        return $data;

    }

    //获取用户列表数目
    public function admin_count($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.groupname')
                ->table('admininfo','A')
                ->add_table('groupinfo','B','A.gid = B.id')
                ->where('A.adminname like "%'.$search.'%"')
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('admininfo')->where('id='.$id)->find();
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
        $userId=$this->model->table('admininfo')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $id=$this->model->table('admininfo')->data($data)->where("id=".$data["id"])->update();
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