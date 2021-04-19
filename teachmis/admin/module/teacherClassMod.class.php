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
    public function getrejectedclass()
    {}
    /**
     * 导入学生成绩
    */
    public function importrejected()
    {
        //接收前台文件
        $ex = $_FILES['excel'];
        //重设置文件名
        $filename = time().substr($ex['name'],stripos($ex['name'],'.'));
        $path = __ROOTDIR__.'/excel/'.$filename;//设置移动路径
        move_uploaded_file($ex['tmp_name'],$path);
        //表用函数方法 返回数组
        $data = $this->_readExcel($path);
        //处理excel表中学生成绩数据
        $menu=$data[0];
        if($menu[0]=='考试名称' && $menu[1]=='班级' && $menu[2]=='学生' && $menu[3]=='课程' && $menu[4]=='分数')
        {
            $errorList=array();
            for($i=1;$i<count($data);$i++)
            {
                $error=array();
                $rejectedStudent=array();
                $v=$data[$i];
                if(empty($v[0]) || empty($v[1]) || empty($v[2]) || empty($v[3]) || empty($v[4]))
                {
                    $error['row']=$i;
                    $error['msg']='本行插入数据不能为空！';
                    $error['data']=$v;
                    $errorList[]=$error;
                    continue;
                }
                //查看考试名称是否存在，并做对应处理
                if($v[0])
                {
                    $rejectedInfo=model('rejected')->info('schoolId='.$_SESSION["user_yg"]["schoolId"].' and rejectedName="'.$v[0].'"');
                    if(empty($rejectedInfo))
                    {
                        $insertData['rejectedName']=$v[0];
                        $rejectedStudent['rejectedId']=model('rejected')->add($insertData);
                    }else{
                        $rejectedStudent['rejectedId']=$rejectedInfo['id'];
                    }
                }
                //查看考试班级是否存在
                if($v[1])
                {
                    $classInfo=model('classs')->searchInfo('schoolId='.$_SESSION["user_yg"]["schoolId"].' and classCode="'.$v[1].'"');
                    if(empty($classInfo))
                    {
                        $error['row']=$i;
                        $error['msg']='本行班级不存在！';
                        $error['data']=$v;
                        $errorList[]=$error;
                        continue;
                    }else{
                        $rejectedStudent['classId']=$classInfo['classId'];
                    }
                }
                if($v[2])
                {
                    $studentInfo=model('users')->searchInfo('classId='.$rejectedStudent['classId'].' and studentName="'.$v[2].'"');
                    if(empty($studentInfo))
                    {
                        $error['row']=$i;
                        $error['msg']='本行班级对应学生不存在！';
                        $error['data']=$v;
                        $errorList[]=$error;
                        continue;
                    }else{
                        $rejectedStudent['studentId']=$studentInfo['usersId'];
                    }
                }
                $rejectedStudent['courseId']=$v[3];
                $rejectedStudent['score']=$v[4];
                $info=model('rejectedStudent')->info($rejectedStudent);
                if($info)
                {
                    print_r($info);
                    continue;
                }
                $status=model('rejectedStudent')->add($rejectedStudent);
                if(!$status)
                {
                    $error['row']=$i;
                    $error['msg']='插入失败！';
                    $error['data']=$v;
                    $errorList[]=$error;
                    continue;
                }
            }
            $this->msg('导入成功！',0,$errorList);
        }else{
            $this->msg('表头不对！',1);
        }
    }
    //创建一个读取excel数据，可用于入库
    public function _readExcel($path)
    {
        //引用PHPexcel 类
        include_once(CP_PATH.'PHPExcel/PHPExcel.php');
        include_once(CP_PATH.'PHPExcel/PHPExcel/IOFactory.php');//静态类
        $type = 'Excel2007';//设置为Excel5代表支持2003或以下版本，Excel2007代表2007版
        $xlsReader = PHPExcel_IOFactory::createReader($type);
        $xlsReader->setReadDataOnly(true);
        $xlsReader->setLoadSheetsOnly(true);
        $Sheets = $xlsReader->load($path);
        //开始读取上传到服务器中的Excel文件，返回一个二维数组
        $dataArray = $Sheets->getSheet(0)->toArray();
        return $dataArray;
    }
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
        $num = model('teacherClass')->usersCount($where);
        //获取信息列表
        $list = model('teacherClass')->usersList($where, $limit);
        $this->assign('list', $list);
        $userInfo=model('users')->info($_GET['id']);
        $this->menu_name = $userInfo['studentName'].'学生成绩列表';
        $this->page = $this->page($url, $num, $listRows);
        $this->show();
    }
}
?>