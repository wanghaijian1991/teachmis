<?php
/**
 * 审批管理
 * add 2021-4-1
 */
class auditMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 审批列表
     */
    public function index()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        //获取行数
        $num = model('examinationTeacher')->infocount($_GET['search']);
        //获取信息列表
        $list = model('examinationTeacher')->infolist($_GET['search'], $limit);
        $this->assign('list', $list);
        $this->menu_name = '审批列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }

    /**
     * 审核
    */
    public function audit()
    {
        $info=model('examinationTeacher')->info(array('id='.$_POST['id'],'teacherId='.$_SESSION["user_yg"]["id"]));
        if($info['status']!=1)
        {
            $this->msg('当前审核数据不存在！', 1);
        }
        if($_POST['type']==0)
        {
            $data['status']=2;
        }else{
            $data['status']=-1;
        }
        $where['id']=$_POST['id'];
        $status=model('examinationTeacher')->update($data,$where);
        if($status)
        {
            if($_POST['type']==0)
            {
                $teacherAttendance['askLeaveStatus']=1;
            }else{
                $teacherAttendance['askLeaveStatus']=-1;
            }
            $teacherAttendance['id']=$info['applyId'];
            $status_ta=model('teacherAttendance')->edit($teacherAttendance);
            if($status_ta && $_POST['type']==0)
            {
                $sort=$info['sort']+1;
                $infoList=model('examinationTeacher')->info(array('applyId='.$info['applyId'],'status in(0,1)','sort='.$sort));
                if(!$infoList)
                {
                    $teacherAttendance['askLeaveStatus']=2;
                    $teacherAttendance['id']=$info['applyId'];
                    $status_ta=model('teacherAttendance')->edit($teacherAttendance);
                }else{
                    $data['status']=1;
                    $where['id']=$infoList['id'];
                    $status=model('examinationTeacher')->update($data,$where);
                }
            }
        }
        $this->msg('审核成功！',0);
    }

    /**
     * 驳回
    */
    public function rejected(){}
}
?>