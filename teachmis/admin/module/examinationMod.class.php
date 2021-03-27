<?php
/**
 * 审批管理
 * add 2021-3-22
 */
class examinationMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 审批分类列表
     */
    public function setTypeList()
    {
        $listRows=20;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('examinationtype')->examinationtypeCount($_GET['search']);
        //获取信息列表
        $list=model('examinationtype')->examinationtypeList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='审批分类列表';
        $this->page=$this->page($url,$num,$listRows);
        $this->show();
    }

    /**
     * 添加课程表
     */
    public function add()
    {
        $this->teachers=model("teachers")->list_select('');
        $this->action_name='添加审批流程';
        $this->action='add';
        $this->show('examination/info');
    }

    /**
     * 保存
     */
    public function add_save() {
        $examinationtype['type']=$_POST['type'];
        $examinationtype['typeName']=$_POST['typeName'];
        $examinationtype['auditArchitecture']=$_POST['auditArchitecture'];
        $examinationtype['auditProcess']='';
        if($_POST['teacherId1'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId1'];
        }
        if($_POST['teacherId2'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId2'];
        }
        if($_POST['teacherId3'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId3'];
        }
        $examinationtype['auditProcess']=trim($examinationtype['auditProcess'],',');
        model('examinationtype')->add($examinationtype);
        $this->msg('添加审批流程成功！',0);
    }

    //审批流程修改
    public function edit() {
        $id=$_GET['id'];
        $this->teachers=model("teachers")->list_select('');
        $info=model('examinationtype')->info(array('id='.$id));
        $this->assign("info",$info);
        $this->action_name='编辑审批流程';
        $this->action='edit';
        $this->show('examination/info');
    }

    //保存审批流程修改
    public function edit_save() {
        //录入模型处理
        $examinationtype['id']=$_POST['id'];
        $examinationtype['type']=$_POST['type'];
        $examinationtype['typeName']=$_POST['typeName'];
        $examinationtype['auditArchitecture']=$_POST['auditArchitecture'];
        $examinationtype['auditProcess']='';
        if($_POST['teacherId1'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId1'];
        }
        if($_POST['teacherId2'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId2'];
        }
        if($_POST['teacherId3'])
        {
            $examinationtype['auditProcess'].=','.$_POST['teacherId3'];
        }
        model('examinationtype')->edit($examinationtype);
        $this->msg('修改审批流程成功！',0);
    }

    //审批流程删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('examinationtype')->del($id);
    }
}
?>