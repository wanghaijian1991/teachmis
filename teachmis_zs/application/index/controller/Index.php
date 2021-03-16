<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use think\View;
use app\index\controller\Common;
use think\Db;
use think\Model;

class Index extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $Common=new Common();
        $Common->checkLogin();
    }

    public function index()
    {
        $xys=array();
        $session=Session::get();
        $errorNum=Db::table('att_userQuestion')->where("usersId=".$session["id"]." and status=1")->count();
        $num=Db::table('att_userQuestion')->where("usersId=".$session["id"])->count();
        if($num)
        {
            $this->assign('error',ceil(($errorNum*100)/$num));
        }else{
            $this->assign('error','空');
        }
        $users=Db::table('att_users')->where("usersId=".$session["id"])->find();
        $paperNum=Db::table('att_paper')->where("classId=".$users["classId"])->count();
        $pNum=Db::table('att_myOperation')->where("usersId=".$session["id"])->count();
        if($paperNum-$pNum>=0)
        {
            $wnum=$paperNum-$pNum;
        }else{
            $wnum=0;
        }
        $this->assign("paperNum",$wnum);
        $beginLastweek=date("Y-m-d H:i:s",mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')));
        $endLastweek=date("Y-m-d H:i:s",mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y')));
        $bookChapters=Db::table('att_bookChapters')->where("bookChaptersFid=0 and gradeId=".$users["gradeId"])->select();
        foreach($bookChapters as $k=>$b)
        {
            $lastnum=Db::table('att_userQuestion uq,att_questions q')->where("uq.questionsId = q.questionsId and q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"]." and uq.createTime>'".$beginLastweek."' and uq.createTime<'".$endLastweek."'")->count();
            $lastcorrectnessNum=Db::table('att_userQuestion uq,att_questions q')->where("uq.questionsId = q.questionsId and q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"]." and uq.createTime>'".$beginLastweek."' and uq.createTime<'".$endLastweek."' and uq.status=1")->count();
            if($lastnum==0)$lastnum=1;
            $bookChapters[$k]["correctLast"]=ceil($lastcorrectnessNum*100/$lastnum);
            $thisnum=Db::table('att_userQuestion uq,att_questions q')->where("uq.questionsId = q.questionsId and q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"]." and uq.createTime>'".$endLastweek."'")->count();
            $thiscorrectnessNum=Db::table('att_userQuestion uq,att_questions q')->where("uq.questionsId = q.questionsId and q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"]." and uq.createTime>'".$endLastweek."' and uq.status=1")->count();
            if($thisnum==0)$thisnum=1;
            $bookChapters[$k]["correctThis"]=ceil($thiscorrectnessNum*100/$thisnum);
            if($bookChapters[$k]["correctLast"]==0)$bookChapters[$k]["correctLast"]=1;
            if($bookChapters[$k]["correctThis"]-$bookChapters[$k]["correctLast"]>0)
            {
                $bookChapters[$k]["upStatus"]=1;
                if($bookChapters[$k]["correctLast"])
                {
                    $bookChapters[$k]["upNum"]=ceil(($bookChapters[$k]["correctThis"]-$bookChapters[$k]["correctLast"])*100/$bookChapters[$k]["correctLast"]);
                }else{
                    $bookChapters[$k]["upNum"]=0;
                }
            }else if($bookChapters[$k]["correctThis"]-$bookChapters[$k]["correctLast"]==0){
                $bookChapters[$k]["upStatus"]=2;
                $bookChapters[$k]["upNum"]=0;
            }else{
                $bookChapters[$k]["upStatus"]=0;
                $bookChapters[$k]["upNum"]=ceil(($bookChapters[$k]["correctLast"]-$bookChapters[$k]["correctThis"])*100/$bookChapters[$k]["correctLast"]);
            }
            $xyList=array();
            //$xyinfo=$this->model->field('date_format(uq.createTime,"%Y-%m") as num,sum(uq.status) as snum,count(*) as znum')->table('userQuestion','uq')->add_table('questions','q','uq.questionsId = q.questionsId')->where("q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"])->group("date_format(uq.createTime,'%Y-%m')")->order("num ASC")->select();
            $time=date("Y-m", strtotime("-1 year"));
            $timeArr=explode("-",$time);
            for($xi=0;$xi<12;$xi++)
            {
                if(($timeArr[1]+$xi)>12)
                {
                    $m=$timeArr[1]+$xi-12;
                    if($m<10)
                    {
                        $m='0'.$m;
                    }
                    $y=$timeArr[0]+1;
                }else{
                    $m=$timeArr[1]+$xi;
                    if($m<10)
                    {
                        $m='0'.$m;
                    }
                    $y=$timeArr[0];
                }
                $xyinfo=array();
                $xyinfo=Db::field('date_format(uq.createTime,"%Y年%m月") as num,sum(uq.status) as snum,count(*) as znum')->table('att_userQuestion uq,att_questions q')->where("uq.questionsId = q.questionsId and q.bookChaptersId like '%,".$b["bookChaptersId"].",%' and uq.usersId=".$session["id"]." and date_format(uq.createTime,'%Y-%m')='".$y."-".$m."'")->group('date_format(uq.createTime,"%Y年%m月")')->order("num ASC")->find();
                //print_r($this->model);
                if($xyinfo)
                {
                    if($xyinfo["num"]){
                        $xyList[$xi]["x"]=$xi;
                        if($xyinfo["znum"]==0)$xyinfo["znum"]=1;
                        if(!$xyinfo["snum"])$xyinfo["snum"]=0;
                        $xyList[$xi]["y"]=ceil($xyinfo["snum"]*100/$xyinfo["znum"]);
                        $xyList[$xi]["num"]=$y."年".$m."月";
                    }
                }
            }
            if($xyList)
            {
                $xy["bclist"]=$xyList;
                $xy["name"]=$b["bookChaptersName"];
                $xy["color"]='#AFE9FF';
                $xys[]=$xy;
            }
        }
        $this->assign("xy",json_encode($xys));
        $this->assign("bookChapters",$bookChapters);
        return $this->fetch('index',[],['__PUBLIC__'=>'/static']);
        //return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="ad_bd568ce7058a1091"></think>';
    }
    public function ceshi()
    {
        $parems=input('param.');
        print_r($parems);
    }
}
