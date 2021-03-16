<?php
//公司部门管理
class areaadminMod extends commonMod {

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        $listRows=20;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('areaadmin')->admin_count($_GET['search']);
        //获取信息列表
        $list=model('areaadmin')->admin_list($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->page=$this->page($url,$num,$listRows);
        $this->show();
    }

    //用户添加
    public function add() {
        /*$this->list=model('company')->com_list($limit);*/
        $this->list=$this->model->query("select * from caiba_staffs");
        $this->user_group=model('groupinfo')->admin_list();
        $this->action_name='添加';
        $this->action='add_save';
        $this->show('areaadmin/addinfo');
    }
    //动态提取下级部门列表
    public function downlist() {
        $list=model("company")->flist(in($_GET["id"]));
    }


    public function add_save() {
        $data=in($_POST);
        //添加考核信息
        $status=model('areaadmin')->add($data);
        if(!$status)
        {
            echo "<script>alert('登录名已存在！');window.location.href='".__APP__."/areaadmin/index'</script>";exit;
        }
        header('Location: '.__APP__.'/areaadmin/index');
    }

    //用户修改
    public function edit() {
        $id=$_GET['id'];
        $this->info=model('company')->info($id);
        $this->list=model('company')->com_list($limit);
        $this->action_name='编辑';
        $this->action='edit_save';
        $this->show('company/addinfo');
    }

    //保存用户修改
    public function edit_save() {
        $data=in($_POST);
        model("company")->update($data);
        header('Location: '.__APP__.'/company/index');
    }

    //用户删除
    public function del() {
        $id=intval($_GET['id']);
        model('company')->del($id);
    }

}