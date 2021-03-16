<?php
class areaadminModel extends commonModel
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
        $user=$this->model->table('admininfo')->where('id='.$uid)->find();
        $user_group=$this->model->table('groupinfo')->where('id='.$user['gid'])->find();
        $user['gname']=$user_group['name'];
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
        $data=$this->model->field('A.*,B.groupname,B.grouppower')
                ->table('admininfo','A')
                ->add_table('groupinfo','B','A.gid = B.id')
				->order("B.id ASC ")
                ->where('A.adminname like "%'.$search.'%"')
				->limit($limit)
                ->select();
        return $data;

    }

    //获取用户列表数目
    public function admin_count($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*,B.groupname,B.grouppower')
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
        $info=$this->model->query("select * from caiba_staffs where staffId=".$data["staffId"]);
        $status=$this->model->table('admininfo')->where('adminname="'.$info[0]["loginName"].'"')->find();
        if($status)
        {
            return -1;
        }
        $target["adminname"]=$info[0]["loginName"];
        $target["nicename"]=$info[0]["staffName"];
        $target["areaId"]=$info[0]["areas_id"];
        $target["password"]=md5($data["password"]);
        $target["staffId"]=$data["staffId"];
        $target["gid"]=$data["gid"];
        return $this->model->table('admininfo')->data($target)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
		$info["fid"]=$data["fid"];
		$info["name"]=$data["nicename"];
		$list["name"]=$data["nicenames"];
		$info["status"]=1;
		$id=$this->model->table("department")->data($info)->where($list)->update();
        $condition['id']=intval($data['id']);
        $id=$this->model->table('admininfo')->data($data)->where($condition)->update();
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
    public function list_fid($id)
    {
        $data=$this->model->table('admininfo')->where('fid='.$id)->select();
        return $data;
    }

}

?>