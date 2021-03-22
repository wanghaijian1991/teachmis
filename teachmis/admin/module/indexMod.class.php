<?php
class indexMod extends commonMod
{
    public function __construct()
    {
        parent::__construct();
    }
    
    // 显示管理后台首页
    public function index()
    {
        $errorNum=$this->model->field('*')->table('userQuestion')->where("usersId=".$_SESSION["user_yg"]["id"]." and status=1")->count();
        $num=$this->model->field('*')->table('userQuestion')->where("usersId=".$_SESSION["user_yg"]["id"])->count();
        if($num)
        {
            $this->assign('error',ceil(($errorNum*100)/$num));
        }else{
            $this->assign('error','空');
        }
        $users=$this->model->field('*')->table('users')->where("usersId=".$_SESSION["user_yg"]["id"])->find();
        $paperNum=$this->model->field('*')->table('paper')->where("classId=".$users["classId"])->count();
        $pNum=$this->model->field('*')->table('myOperation')->where("usersId=".$_SESSION["user_yg"]["id"])->count();
        if($paperNum-$pNum>=0)
        {
            $wnum=$paperNum-$pNum;
        }else{
            $wnum=0;
        }
        $this->assign("paperNum",$wnum);
        $beginLastweek=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')));
        $endLastweek=date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')));
        $bookChapters=$this->model->field('*')->table('bookChapters')->where("bookChaptersFid=0 and gradeId=".$users["gradeId"])->select();
        if($bookChapters) {
            foreach ($bookChapters as $k => $b) {
                $lastnum = $this->model->table('userQuestion', 'uq')->add_table('questions', 'q', 'uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%," . $b["bookChaptersId"] . ",%' and uq.usersId=" . $_SESSION["user_yg"]["id"] . " and uq.createTime>'" . $beginLastweek . "' and uq.createTime<'" . $endLastweek . "'")->count();
                $lastcorrectnessNum = $this->model->table('userQuestion', 'uq')->add_table('questions', 'q', 'uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%," . $b["bookChaptersId"] . ",%' and uq.usersId=" . $_SESSION["user_yg"]["id"] . " and uq.createTime>'" . $beginLastweek . "' and uq.createTime<'" . $endLastweek . "' and uq.status=1")->count();
                if ($lastnum == 0) $lastnum = 1;
                $bookChapters[$k]["correctLast"] = ceil($lastcorrectnessNum * 100 / $lastnum);
                $thisnum = $this->model->table('userQuestion', 'uq')->add_table('questions', 'q', 'uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%," . $b["bookChaptersId"] . ",%' and uq.usersId=" . $_SESSION["user_yg"]["id"] . " and uq.createTime>'" . $endLastweek . "'")->count();
                $thiscorrectnessNum = $this->model->table('userQuestion', 'uq')->add_table('questions', 'q', 'uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%," . $b["bookChaptersId"] . ",%' and uq.usersId=" . $_SESSION["user_yg"]["id"] . " and uq.createTime>'" . $endLastweek . "' and uq.status=1")->count();
                if ($thisnum == 0) $thisnum = 1;
                $bookChapters[$k]["correctThis"] = ceil($thiscorrectnessNum * 100 / $thisnum);
                if ($bookChapters[$k]["correctLast"] == 0) $bookChapters[$k]["correctLast"] = 1;
                if ($bookChapters[$k]["correctThis"] - $bookChapters[$k]["correctLast"] > 0) {
                    $bookChapters[$k]["upStatus"] = 1;
                    if ($bookChapters[$k]["correctLast"]) {
                        $bookChapters[$k]["upNum"] = ceil(($bookChapters[$k]["correctThis"] - $bookChapters[$k]["correctLast"]) * 100 / $bookChapters[$k]["correctLast"]);
                    } else {
                        $bookChapters[$k]["upNum"] = 0;
                    }
                } else if ($bookChapters[$k]["correctThis"] - $bookChapters[$k]["correctLast"] == 0) {
                    $bookChapters[$k]["upStatus"] = 2;
                    $bookChapters[$k]["upNum"] = 0;
                } else {
                    $bookChapters[$k]["upStatus"] = 0;
                    $bookChapters[$k]["upNum"] = ceil(($bookChapters[$k]["correctLast"] - $bookChapters[$k]["correctThis"]) * 100 / $bookChapters[$k]["correctLast"]);
                }
                $xyList = array();
                //$xyinfo=$this->model->field('date_format(uq.createTime,"%Y-%m") as num,sum(uq.status) as snum,count(*) as znum')->table('userQuestion','uq')->add_table('questions','q','uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$_SESSION["user_yg"]["id"])->group("date_format(uq.createTime,'%Y-%m')")->order("num ASC")->select();
                $time = date("Y-m", strtotime("-1 year"));
                $timeArr = explode("-", $time);
                for ($xi = 0; $xi < 12; $xi++) {
                    if (($timeArr[1] + $xi) > 12) {
                        $m = $timeArr[1] + $xi - 12;
                        if ($m < 10) {
                            $m = '0' . $m;
                        }
                        $y = $timeArr[0] + 1;
                    } else {
                        $m = $timeArr[1] + $xi;
                        if ($m < 10) {
                            $m = '0' . $m;
                        }
                        $y = $timeArr[0];
                    }
                    $xyinfo = array();
                    $xyinfo = $this->model->field('date_format(uq.createTime,"%Y年%m月") as num,sum(uq.status) as snum,count(*) as znum')->table('userQuestion', 'uq')->add_table('questions', 'q', 'uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%," . $b["bookChaptersId"] . ",%' and uq.usersId=" . $_SESSION["user_yg"]["id"] . " and date_format(uq.createTime,'%Y-%m')='" . $y . "-" . $m . "'")->group('date_format(uq.createTime,"%Y年%m月")')->order("num ASC")->select();
                    //print_r($this->model);
                    if ($xyinfo[0]["num"]) {
                        $xyList[$xi]["x"] = $xi;
                        if ($xyinfo[0]["znum"] == 0) $xyinfo[0]["znum"] = 1;
                        if (!$xyinfo[0]["snum"]) $xyinfo[0]["snum"] = 0;
                        $xyList[$xi]["y"] = ceil($xyinfo[0]["snum"] * 100 / $xyinfo[0]["znum"]);
                        $xyList[$xi]["num"] = $y . "年" . $m . "月";
                    }
                }
                if ($xyList) {
                    $xy["bclist"] = $xyList;
                    $xy["name"] = $b["bookChaptersName"];
                    $xy["color"] = '#AFE9FF';
                    $xys[] = $xy;
                }
            }
        }else{
            $xys=array();
            $bookChapters=array();
        }
        //$xys[]=$xy;
        //echo json_encode($xys);
        $this->assign("xy",json_encode($xys));
        $this->assign("bookChapters",$bookChapters);
        $this->display();
    }

    // 显示管理后台欢迎页
    public function home()
    {
        /*require (__ROOTDIR__.'/config.php');
        $this->config_array=$config;
        $this->show();*/
    }

    //环境信息
    public function tool_system(){
        $this->display();
    }

    public function tool_bom(){
        $str=$this->tool_bom_dir(__ROOTDIR__);
        $this->msg($str.'所有BOM清除完毕！');
    }

    //清除BOM
    public function tool_bom_dir($basedir){
        if ($dh = opendir($basedir)) {
            $str='';
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..'){
                    if (!is_dir($basedir."/".$file)) {
                        if($this->tool_bom_clear("$basedir/$file")){
                            $str.= "文件 [$basedir/$file] 发现BOM并已清除<br>";
                        }
                    }else{
                        $dirname = $basedir."/".$file;
                        $this->tool_bom_dir($dirname);
                    }
                }
            }
        closedir($dh);
        }
        return $str;
    }
    public function tool_bom_clear($filename){
        $contents = file_get_contents($filename);
        $charset[1] = substr($contents, 0, 1);
        $charset[2] = substr($contents, 1, 1);
        $charset[3] = substr($contents, 2, 1);
        if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
                $rest = substr($contents, 3);
                $this->rewrite ($filename, $rest);
                return true;
        }
    }

    public function rewrite ($filename, $data) {
        $filenum = fopen($filename, "w");
        flock($filenum, LOCK_EX);
        fwrite($filenum, $data);
        fclose($filenum);
    }

    function paperParse()
    {
        $this->display();
    }
	
}

?>
