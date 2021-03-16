<?php
class employeesModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取动态列表
    public function elist($id,$status)
    {  
		$list=$this->model->table('attinfo')->where("user_id=".$id."".$status)->order("id DESC")->select();
		return $list;
    }
    //获取行数
	public function ecount($condition)
	{
        $count=$this->model->table('dynamic')->where($condition)->count();  
		return $count;
	}
	//添加动态信息
	public function add($data)
	{
		return $this->model->table('attinfo')->data($data)->insert();
	}
	//修改动态信息
	public function update($data)
	{
		return $this->model->table('attinfo')->data($data)->where("id=".$data["id"])->update();
	}
	//查询动态信息
	public function select($id)
	{
		return $this->model->table('dynamic')->where("id=".$id)->find();
	}
	//查询详细信息
	public function find($id)
	{
		return $this->model->table('attinfo')->where("id=".$id)->find();
	}
	//查询员工信息
	public function userfind($id)
	{
		$data=$this->model->field('A.*,B.name')
                ->table('admininfo','A')
                ->add_table('department','B','A.fid = B.id')
				->where("A.did=".$id)
                ->find();
        return $data;
	}
}

?>