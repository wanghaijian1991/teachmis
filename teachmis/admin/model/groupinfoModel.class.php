<?php
//用户组数据处理
class groupinfoModel extends commonModel {

	public function __construct()
    {
        parent::__construct();
    }

    //获取用户组列表
    public function admin_list()
    {
        $list=$this->model->table('groupinfo')->where('schoolId='.$_SESSION["user_yg"]["schoolId"])->order('id asc')->select();
        return $list;
    }

    //获取用户组内容
    public function info($id)
    {
        return $this->model->table('groupinfo')->where('id='.$id)->find();
    }

    //添加用户组内容
    public function add($data)
    {
        $data["schoolId"]=$_SESSION["user_yg"]["id"];
        return $this->model->table('groupinfo')->data($data)->insert();
    }

    //编辑用户组内容
    public function edit($data)
    {
        $condition['id']=intval($data['id']);
        return $this->model->table('groupinfo')->data($data)->where($condition)->update();
    }

    //删除用户组内容
    public function del($id)
    {
        $this->model->table('admininfo')->where('gid='.intval($id))->delete(); 
        return $this->model->table('groupinfo')->where('id='.intval($id))->delete(); 
    }
	

}