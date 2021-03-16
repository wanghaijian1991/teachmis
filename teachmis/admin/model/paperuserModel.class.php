<?php
class paperuserModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取列表
    public function test_list($paper_id,$limit)
    {
        $data=$this->model->query('select a.*,ad.adminname from att_paper_user a,att_admininfo ad where a.user_id=ad.id and a.paper_id='.$paper_id.' ORDER BY a.correct_num DESC LIMIT '.$limit);
		return $data;
    }
    //获取行数
	public function count($paper_id)
	{
        $data=$this->model->query('select a.*,ad.adminname from att_paper_user a,att_admininfo ad where a.user_id=ad.id and a.paper_id='.$paper_id.' ORDER BY a.correct_num DESC');
		return count($data);
	}
    //获取列表
    public function test_list_s($paper_id)
    {
        $data=$this->model->query('select a.*,ad.adminname from att_paper_user a,att_admininfo ad where a.user_id=ad.id and a.paper_id='.$paper_id.' ORDER BY a.correct_num DESC');
        return $data;
    }
	//添加信息
	public function add($data)
	{
		return $this->model->table('paper_user')->data($data)->insert();
	}
	//修改动态信息
	public function update($data)
	{
		return $this->model->table('paper_user')->data($data)->where("paper_user_id=".$data["paper_user_id"])->update();
	}
	//查询信息
	public function info($id,$user_id)
	{
		return $this->model->table('paper_user')->where("paper_id=".$id." and user_id=".$user_id)->find();
	}
	//查询上级部门名称
	public function flist()
	{
		return $this->model->table('department')->where("status=0")->select();
	}
	
	//删除
	public function del($id)
	{
		return $this->model->table("paper")->where("paper_id=".$id)->delete();
	}
	public function fnameselect($fid)
	{
		$name=$this->model->table('department')->where("id=".$fid)->find();
		return $name;
	}
}

?>