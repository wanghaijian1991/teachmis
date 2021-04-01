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
        $teacherAskLeave['askLeaveType']=$_POST['askLeaveType'];
        $teacherAskLeave['askLeaveTimeStart']=$_POST['askLeaveTimeStart'];
        $teacherAskLeave['askLeaveTimeEnd']=$_POST['askLeaveTimeEnd'];
        $teacherAskLeave['askLeaveCause']=$_POST['askLeaveCause'];
        $teacherAskLeave['askLeaveAgent']=$_POST['askLeaveAgent'];
        $teacherAskLeave['auditProcess']='';
        if($_POST['teacherId1'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId1'];
        }
        if($_POST['teacherId2'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId2'];
        }
        if($_POST['teacherId3'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId3'];
        }
        $teacherAskLeave['auditProcess']=trim($teacherAskLeave['auditProcess'],',');
        $id = model('teacherAttendance')->add($teacherAskLeave);
        $auditProcess=explode(',',$teacherAskLeave['auditProcess']);
        $data=array();
        foreach($auditProcess as $k=>$v)
        {
            $arr['schoolId']=$_SESSION["user_yg"]["schoolId"];
            $arr['teacherId']=$v;
            $arr['applyId']=$id;
            $arr['type']=0;
            if($k==0)
            {
                $arr['status']=1;
            }
            $arr['createTime']=date("Y-m-d H:i:s");
            $data[]=$arr;
        }
        model('examinationTeacher')->add($data);
        $this->msg('添加请假申请成功！', 0, $id);
    }

    //修改
    public function edit() {
        $id=$_GET['id'];
        $this->teachers = model("teachers")->list_select('');
        $this->department=model("department")->list_select('');
        $info=model('teacherAttendance')->info(array('id='.$id));
        $auditProcess=explode(',',$info['auditProcess']);
        $this->assign("info",$info);
        $this->assign("auditProcess",$auditProcess);
        $this->action_name='编辑申请';
        $this->action='edit';
        $this->show('teacherAttendance/info');
    }

    //保存申请修改
    public function edit_save() {
        $teacherAskLeave['id']=$_POST['id'];
        $teacherAskLeave['askLeaveType']=$_POST['askLeaveType'];
        $teacherAskLeave['askLeaveTimeStart']=$_POST['askLeaveTimeStart'];
        $teacherAskLeave['askLeaveTimeEnd']=$_POST['askLeaveTimeEnd'];
        $teacherAskLeave['askLeaveCause']=$_POST['askLeaveCause'];
        $teacherAskLeave['askLeaveAgent']=$_POST['askLeaveAgent'];
        $teacherAskLeave['auditProcess']='';
        if($_POST['teacherId1'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId1'];
        }
        if($_POST['teacherId2'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId2'];
        }
        if($_POST['teacherId3'])
        {
            $teacherAskLeave['auditProcess'].=','.$_POST['teacherId3'];
        }
        $teacherAskLeave['auditProcess']=trim($teacherAskLeave['auditProcess'],',');
        //录入模型处理
        model('teacherAttendance')->edit($teacherAskLeave);
        $auditProcess=explode(',',$teacherAskLeave['auditProcess']);
        $data=array();
        $sort=0;
        foreach($auditProcess as $key=>$v)
        {
            $arr=array();
            $arr['schoolId']=$_SESSION["user_yg"]["schoolId"];
            $arr['teacherId']=$v;
            $arr['applyId']=$teacherAskLeave['id'];
            $arr['type']=0;
            if($key==0)
            {
                $arr['status']=1;
            }
            $arr['sort']=$key;
            $arr['createTime']=date("Y-m-d H:i:s");
            $data[]=$arr;
        }
        $update=array();
        $update['status']=-2;
        $where['applyId']=$teacherAskLeave['id'];
        model('examinationTeacher')->update($update,$where);
        model('examinationTeacher')->add($data);
        $this->msg('修改申请成功！',0);
    }

    //部门删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('department')->del($id);
    }
}
?>