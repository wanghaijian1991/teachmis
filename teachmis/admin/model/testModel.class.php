<?php
class testModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取列表
    public function test_list($limit)
    {  
		$list=$this->model->table('test')->where("status=0")->order('hang DESC,page ASC')->limit($limit)->select();

		return $list;
    }
    //获取列表
    public function select_in($str)
    {
        $list=$this->model->query('select * from att_test where test_id in ('.$str.')');

        return $list;
    }
    //获取行数
	public function count()
	{
        $count=$this->model->table('test')->where("status=0")->count();
		return $count;
	}
	//添加信息
	public function add($data)
	{
		return $this->model->table('test')->data($data)->insert();
	}
	//修改动态信息
	public function update($data)
	{
		return $this->model->table('test')->data($data)->where("test_id=".$data["test_id"])->update();
	}
	//查询信息
	public function info($id)
	{
		return $this->model->table('test')->where("test_id=".$id)->find();
	}
    //查询信息
    public function info_name($test_name)
    {
        return $this->model->table('test')->where("test_name='".$test_name."'")->find();
    }
	//查询上级部门名称
	public function flist($hang)
	{
	    $data["status"]=0;
	    $data["hang"]=$hang;
		return $this->model->table('test')->where($data)->order('page ASC')->select();
	}
	
	//删除
	public function del($id)
	{
		return $this->model->table("test")->where("test_id=".$id)->delete();
	}
	public function fnameselect($fid)
	{
		$name=$this->model->table('department')->where("id=".$fid)->find();
		return $name;
	}
}

?>