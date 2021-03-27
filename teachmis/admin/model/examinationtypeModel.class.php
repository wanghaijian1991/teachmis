<?php
class examinationtypeModel extends commonModel
{
    public function __construct()
    {
        parent::__construct();
    }

    //获取列表
    public function examinationtypeList($search, $limit)
    {
        $data = $this->model->field('A.*')
            ->table('examinationtype', 'A')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->limit($limit)
            ->select();
        if($data)
        {
            foreach($data as &$v)
            {
                $config=$this->config['type'];
                $v['type']=$config[$v['type']];
                switch($v['auditArchitecture'])
                {
                    case 0:
                        $v['auditArchitecture']='自定义';
                        break;
                    case 1:
                        $v['auditArchitecture']='当前部门';
                        break;
                    case 2:
                        $v['auditArchitecture']='全流程';
                        break;
                }
                $teacherinfo=array();
                if($v['auditProcess'])
                {
                    $teacherinfo=$this->model->field('group_concat(teacherName) as auditProcess')->table('teacher')->where('schoolId='.$_SESSION["user_yg"]["schoolId"].' and teacherId in('.$v['auditProcess'].')')->group('schoolId')->order('teacherPosition DESC')->find();
                }
                $v['auditProcess']=$teacherinfo['auditProcess']?$teacherinfo['auditProcess']:'';
                if($v['status']==0)
                {
                    $v['status']='正常';
                }else{
                    $v['status']='删除';
                }
            }
        }
        return $data;

    }

    //获取总数
    public function examinationtypeCount($search)
    {
        $num = $this->model->field('A.*')
            ->table('examinationtype', 'A')
            ->where($search)
            ->where("A.schoolId=" . $_SESSION["user_yg"]["schoolId"])
            ->count();
        return $num;

    }

    //添加课程表内容
    public function add($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $examinationtypeId=$this->model->table('examinationtype')->data($data)->insert();
        return $examinationtypeId;
    }

    //添加课程表详情内容
    public function addInfo($data)
    {
        $data['createTime']=date("Y-m-d H:i:s");
        $data['schoolId']=$_SESSION["user_yg"]["schoolId"];
        $examinationtypeId=$this->model->table('examinationtype')->data($data)->insert();
        return $examinationtypeId;
    }
}
?>