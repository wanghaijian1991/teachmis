<?php
class bookChaptersModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function bookChaptersList($search)
    {
        if($search){
            $search=$search." and A.schoolId=".$_SESSION["user_yg"]["schoolId"];
        }else{
            $search="A.schoolId=".$_SESSION["user_yg"]["schoolId"];
        }
        $data=$this->model->field('A.*')
                ->table('bookChapters','A')
                ->where($search)
                ->select();
        if($data)
            foreach($data as $key=>$v)
            {
                $info=$this->model->field('A.bookChaptersId')->table('bookChapters','A')->where("A.bookChaptersFid=".$v["bookChaptersId"])->select();
                if($info)
                {
                    $data[$key]["status"]=1;
                }else{
                    $data[$key]["status"]=0;
                }
            }
        return $data;

    }

    public function bookChaptersCount($search)
    {
        /*$user=$this->current_user();*/
        $num=$this->model->field('A.*')
                ->table('bookChapters','A')
                ->where($search)
                ->where("A.schoolId=".$_SESSION["user_yg"]["schoolId"])
                ->count();
        return $num;

    }

    //获取用户内容
    public function info($id)
    {
        return $this->model->table('bookChapters')->where('bookChaptersId='.$id)->find();
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
        $data['createId']=$_SESSION["user_yg"]["id"];
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $userId=$this->model->table('bookChapters')->data($data)->insert();
    }

    //编辑用户内容
    public function edit($data)
    {
        $data['modTime']=date("Y-m-d H:i:s");
        $data['modId']=$_SESSION["user_yg"]["id"];
        $id=$this->model->table('bookChapters')->data($data)->where("bookChaptersId=".$data["bookChaptersId"])->update();
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
        if($where)
        {
            $where="schoolId=".$_SESSION["user_yg"]["schoolId"]." and ".$where;
        }else{
            $where="schoolId=".$_SESSION["user_yg"]["schoolId"];
        }
        $data=$this->model->table('bookChapters')->where($where)->select();
        return $data;
    }

    function getbookChapters($id)
    {
        $id=trim($id,",");
        $arr=explode(",",$id);
        $data=$this->model->table('bookChapters')->where("schoolId=".$_SESSION["user_yg"]["schoolId"]." and bookChaptersId in(".$id.")")->select();
        $datas=array();
        if($data)
            foreach($data as $d)
            {
                $datas[$d["bookChaptersId"]]=$d["bookChaptersName"];
            }
        $str="";
        foreach($arr as $v)
        {
            $str.=$datas[$v].",";
        }
        return $str;
    }
}

?>