<?php
/**
 * 考勤管理
 * add 2021-3-22
 */
class teacherAttendanceMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 请假申请列表
     */
    public function index()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        //获取行数
        $num = model('teacherAttendance')->infocount($_GET['search']);
        //获取信息列表
        $list = model('teacherAttendance')->infolist($_GET['search'], $limit);
        $this->assign('list', $list);
        $this->menu_name = '请假申请列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }

    /**
     * 新增请假申请
     */
    public function add()
    {
        $examinationtype=model('examinationtype')->info(array('type=0 and status=0'));
        $this->department = model("department")->list_select('');
        $this->teachers = model("teachers")->list_select('');
        $auditProcess=array();
        switch($examinationtype['auditArchitecture'])
        {
            case 0:
                $auditProcess=explode(',',$examinationtype['auditProcess']);
                break;
            case 1:
                $teacher=model("teachers")->info($_SESSION["user_yg"]["id"]);
                $department=model("department")->info(array('id='.$teacher['departmentId']));
                $auditProcess[]=$department['teacherId'];
                break;
            case 2:
                $teacher=model("teachers")->info($_SESSION["user_yg"]["id"]);
                $department=model("department")->info(array('id='.$teacher['departmentId']));
                $str=$department['teacherId'];
                if($department['fid'])
                {
                    $str=$str.",".$this->getDepartmentLeader($department['fid']);
                }
                $auditProcess=explode(',',$str);
                break;
        }
        $this->auditProcess=$auditProcess;
        $this->action_name = '添加请假申请';
        $this->action = 'add';
        $this->show('teacherAttendance/info');
    }

    /**
     * 保存
     */
    public function add_save()
    {
        $departmentInfo['fid'] = $_POST['fid'];
        $departmentInfo['departmentName'] = $_POST['departmentName'];
        $departmentInfo['teacherId'] = $_POST['teacherId'];
        $departmentId = model('department')->addInfo($departmentInfo);
        $this->msg('添加请假申请成功！', 0, $departmentId);
    }

    /**
     * 获取上级部门领导
    */
    public function getDepartmentLeader($id)
    {
        $department=model("department")->info(array('id='.$id));
        $str=$department['teacherId'];
        if($department['fid'])
        {
            $str=$str.",".$this->getDepartmentLeader($department['fid']);
        }
        return $str;
    }
}
?>