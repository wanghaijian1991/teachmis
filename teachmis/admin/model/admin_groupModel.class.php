<?php
//用户组数据处理
class admin_groupModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }

    //获取用户组列表
    public function admin_list()
    {
        $user=model('admin')->current_user();
        return $this->model->table('admin_group')->where('grade>='.$user['grade'])->order('id asc')->select();
    }

    //获取用户组内容
    public function info($id)
    {
        return $this->model->table('admin_group')->where('id='.$id)->find();
    }

    //添加用户组内容
    public function add($data)
    {
        return $this->model->table('admin_group')->data($data)->insert();
    }

    //编辑用户组内容
    public function edit($data)
    {
        $condition['id']=intval($data['id']);
        return $this->model->table('admin_group')->data($data)->where($condition)->update();
    }

    //删除用户组内容
    public function del($id)
    {
        $this->model->table('admin')->where('gid='.intval($id))->delete(); 
        return $this->model->table('admin_group')->where('id='.intval($id))->delete(); 
    }
	

}