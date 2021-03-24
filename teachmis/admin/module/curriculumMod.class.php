<?php
/**
 * 课程管理
 * add 2021-3-22
*/
class curriculumMod extends commonMod
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 课程表列表
    */
    public function index()
    {
        $listRows=20;//每页显示的信息条数
        $url=__URL__.'/index/page-{page}.html';//分页基准网址
        $limit=$this->pagelimit($url,$listRows);

        //获取行数
        $num=model('curriculum')->curriculumCount($_GET['search']);
        //获取信息列表
        $list=model('curriculum')->curriculumList($_GET['search'],$limit);
        $this->assign('list',$list);
        $this->menu_name='课程表列表';
        $this->page=$this->page($url,$num,$listRows);
        $this->show();
    }

    /**
     * 添加课程表
    */
    public function add()
    {
        $this->classlist=model('classs')->list_select('');
        $this->action_name='添加课程表';
        $this->action='add';
        $this->show('curriculum/info');
    }

    /**
     * 保存
    */
    public function add_save() {
        $classInfo=model('classs')->info($_POST['classId']);
        $curriculum['curriculumName']=$classInfo['className'];
        $curriculum['classId']=$_POST['classId'];
        $validation=array(
            array('field'=>'classId','name'=>'班级','type'=>1,'prompt'=>'班级不能为空！')
        );
        $return=$this->validation_field($validation,$_POST);
        if($return['status']==1)
        {
            $this->msg($return['msg'],1);
            return;
        }
        //录入模型处理
        model('curriculum')->add($curriculum);
        $this->msg('添加课程表成功！',0);
    }
}
?>