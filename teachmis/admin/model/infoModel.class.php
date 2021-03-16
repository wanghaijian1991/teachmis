<?php
class infoModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
	//修改用户信息
	public function update($data)
	{
		return $this->model->table('admininfo')->data($data)->where("id=".$data["id"])->update();
	}
	//查询用户信息
	public function info($id)
	{
		$list=$this->model->field('id,duty')->table('admininfo')->where("id=".$id)->find();
		return $list;
	}
    //获取列表
    public function info_list($limit)
    {  
		$list=$this->model->field('A.*,B.name')
                ->table('userinfo','A')
                ->add_table('department','B','A.fid = B.id')
                ->order('A.id DESC')
				->limit($limit)
                ->select();
		return $list;
    }
    //获取公司部门列表
    public function com_list()
    {  
		return $this->model->table('department')->where("status=0")->order('id DESC')->select();
    }
    //获取行数
	public function info_count()
	{
        $count=$this->model->table('userinfo')->count();  
		return $count;
	}
	//添加信息
	public function add($data)
	{
		$info["fid"]=$data["fid"];
		$info["name"]=$data["username"];
		$info["status"]=$data["status"];
		$this->model->table("department")->data($info)->insert();
		return $this->model->table('userinfo')->data($data)->insert();
	}
}

?>