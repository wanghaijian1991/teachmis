<?php
namespace app\index\model;

use think\Db;
use think\Model;
use think\Session;

class Admin extends Model
{
    protected $table = 'user';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    //当前用户信息
    public function current_user() {

        $session=Session::get();
        if($session["userType"]==2)
        {
            $user=DB::table('att_users')->where('usersId='.$session['id'])->find();
        }else if($session["userType"]==1)
        {
            $user=DB::table('att_teacher')->where('teacherId='.$session['id'])->find();
        }else{
            $user=DB::table('att_admininfo')->where('id='.$session['id'])->find();
        }
        $user_group=DB::table('att_groupinfo')->where('id='.$user['gid'])->find();
	    if(isset($user_group['name']))
	    {
            $user['gname']=$user_group['name'];
        }else{
            $user['gname']='';
        }
        if(!isset($user['id']))
        {
            $user['model_power']=$user_group['grouppower'];
        }else  if($user['id']!=1)
        {
            $user['model_power']=$user_group['grouppower'];
        }
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
