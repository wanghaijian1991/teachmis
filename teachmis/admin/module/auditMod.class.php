<?php
/**
 * 考勤管理
 * add 2021-4-1
 */
class auditMod extends commonMod
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
        $num = model('examinationTeacher')->infocount($_GET['search']);
        //获取信息列表
        $list = model('examinationTeacher')->infolist($_GET['search'], $limit);
        $this->assign('list', $list);
        $this->menu_name = '请假申请列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
}
?>