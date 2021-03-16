<?php
class companyModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取列表
    public function com_list($limit)
    {  
		$list=$this->model->table('department')->where("status=0 and adminId=".$_SESSION["user_yg"]["id"])->order('id ASC')->limit($limit)->select();
	
		$cat=new Category(array('id','fid','name','cname'));
		return $cat->getTree($list);
    }
    //获取行数
	public function com_count()
	{
        $count=$this->model->table('department')->where("status=0 and adminId=".$_SESSION["user_yg"]["id"])->count();
		return $count;
	}
	//添加信息
	public function add($data)
	{
	    $data["adminId"]=$_SESSION["user_yg"]["id"];
		return $this->model->table('department')->data($data)->insert();
	}
	//修改动态信息
	public function update($data)
	{
        $data["adminId"]=$_SESSION["user_yg"]["id"];
		return $this->model->table('department')->data($data)->where("id=".$data["id"])->update();
	}
	//查询信息
	public function info($id)
	{
		return $this->model->table('department')->where("id=".$id)->find();
	}
	//查询上级部门名称
	public function flist()
	{
		return $this->model->table('department')->where("status=0 and adminId=".$_SESSION["user_yg"]["id"])->select();
	}
	
	//删除
	public function del($id)
	{
		return $this->model->table("department")->where("id=".$id)->delete();
	}
	public function fnameselect($fid)
	{
		$name=$this->model->table('department')->where("id=".$fid)->find();
		return $name;
	}
}

?>