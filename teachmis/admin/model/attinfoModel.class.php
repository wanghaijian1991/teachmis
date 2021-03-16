<?php
class attinfoModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }
    //获取动态列表
    public function att_list($limit)
    {
        /*$data=$this->model->field('A.*,B.correct_num,B.start_time as exam_start_time,B.end_time as exam_end_time')
            ->table('paper','A')
            ->add_table('paper_user','B','A.paper_id = B.paper_id')
            ->order('A.paper_id DESC')
            ->where('B.user_id='.$_SESSION["user_yg"]["id"].' or A.paper_admin='.$_SESSION["user_yg"]["fid"])
            ->limit($limit)
            ->select();*/
        $data=$this->model->query('SELECT A.*,B.user_id,B.duration,B.correct_num,B.start_time as exam_start_time,B.end_time as exam_end_time FROM att_paper A left outer join att_paper_user B on A.paper_id = B.paper_id  WHERE B.user_id='.$_SESSION["user_yg"]["id"].' and A.paper_admin='.$_SESSION["user_yg"]["fid"].' ORDER BY A.paper_id DESC LIMIT '.$limit);
		return $data;
    }
    //获取行数
	public function att_count()
	{
        $arr=$this->model->query('SELECT A.*,B.correct_num,B.start_time as exam_start_time,B.end_time as exam_end_time FROM att_paper A left outer join att_paper_user B on A.paper_id = B.paper_id  WHERE B.user_id='.$_SESSION["user_yg"]["id"].' and A.paper_admin='.$_SESSION["user_yg"]["fid"].' ORDER BY A.paper_id DESC');
		return count($arr);
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
	//验证是否规定时间已填写
	public function yanzheng($id,$starttime,$endtime)
	{
		return $this->model->table('attinfo')->where("user_id=".$id." and time>".$starttime." and time<".$endtime)->find();
	}
}

?>