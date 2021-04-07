<?php
//我的管理
class teacherClassMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        $where['teacherId']=$_SESSION["user_yg"]["id"];
        //获取行数
        $num = model('users')->usersCount($where);
        //获取信息列表
        $list = model('users')->usersList($where, $limit);
        $this->assign('list', $list);
        $this->menu_name = '学生列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
    /**
     * 查看详情
    */
    public function info()
    {
        $id=$_GET['id'];
        $this->classlist=model('classs')->list_select('');
        $this->grade=model("grade")->list_select('');
        $this->list=model('groupinfo')->admin_list();
        $info=model('users')->info($id);
        $this->assign("info",$info);
        $this->action_name='编辑学生';
        $this->action='edit';
        $this->show();
    }
    /**
     * 查看班级成绩曲线
    */
    public function getrejectedclass(){}
    /**
     * 导入学生成绩
    */
    public function importrejected(){}
    /**
     * 查看学生成绩
    */
    public function getrejected()
    {
        $listRows = 20;//每页显示的信息条数
        $url = __URL__ . '/index/page-{page}.html';//分页基准网址
        $limit = $this->pagelimit($url, $listRows);

        $where['teacherId']=$_SESSION["user_yg"]["id"];
        //获取行数
        $num = model('users')->usersCount($where);
        //获取信息列表
        $list = model('users')->usersList($where, $limit);
        $this->assign('list', $list);
        $this->menu_name = '学生成绩列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
}
?>