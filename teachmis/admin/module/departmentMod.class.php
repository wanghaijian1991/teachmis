<?php
/**
 * 部门管理
 * add 2021-3-22
 */
class departmentMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 部门列表
     */
    public function index()
    {
        $listRows=20;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('department')->departmentCount($_GET['search']);
        //获取信息列表
        $list=model('department')->departmentList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='部门列表';
        $this->page=$this->page($url,$num,$listRows);
        $this->show();
    }

    /**
     * 添加部门
     */
    public function add()
    {
        $this->department=model("department")->list_select('');
        $this->teachers=model("teachers")->list_select('');
        $this->action_name='添加部门';
        $this->action='add';
        $this->show('department/info');
    }

    /**
     * 保存
     */
    public function add_save() {
        $departmentInfo['fid']=$_POST['fid'];
        $departmentInfo['departmentName']=$_POST['departmentName'];
        $departmentInfo['teacherId']=$_POST['teacherId'];
        $departmentId=model('department')->addInfo($departmentInfo);
        $this->msg('添加部门成功！',0,$departmentId);
    }

    //部门修改
    public function edit() {
        $id=$_GET['id'];
        $this->department=model("department")->list_select('');
        $info=model('department')->info($id);
        $this->assign("info",$info);
        $this->action_name='编辑部门';
        $this->action='edit';
        $this->show('department/info');
    }

    //保存部门修改
    public function edit_save() {
        //录入模型处理
        model('department')->edit($_POST);
        $this->msg('修改部门成功！',0);
    }

    //部门删除
    public function del() {
        $id=intval($_GET['id']);
        //录入模型处理
        model('department')->del($id);
    }
}
?>