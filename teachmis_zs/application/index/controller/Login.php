<?php
namespace app\index\controller;

use think\Controller;
use think\View;
use app\index\controller\Common;
use app\index\model\Member;
use think\Session;

class Login extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
    }

    //登录
    public function index()
    {
        $Common=new Common();
        $session['token']=Session::get('token');
        if($session['token'])
        {
            $session['id']=Session::get('id');
            $session['time']=Session::get('time');
            $session['schoolId']=Session::get('schoolId');
            $session['userType']=Session::get('userType');
            $token=$Common->setToken($session);
            if($session['token']==$token)
            {
                header('location:' . __APP__ . '/index');
            }
        }
        return $this->fetch('index',[],['__PUBLIC__'=>'/static']);
    }

    //验证登录
    public function check()
    {
        $Common=new Common();
        $parems['user']=$_REQUEST['user'];
        $parems['password']=$_REQUEST['password'];
        $parems['userType']=$_REQUEST['userType'];
        if($parems['userType']==0)
        {
        }else if($parems['userType']==1)
        {
        }else if($parems['userType']==2)
        {
            $Member=new Member();
            $info=$Member->getMemberInfo($parems);
            $SecretKey=$info['usersSecretKey'];
            $Password=$info['usersPassword'];
            $memberId=$info['usersId'];
            $schoolId=$info["schoolId"];
        }
        if(empty($info))
        {
            $Common->msg('账号不存在！',10001);
        }
        $passwd=$Common->md5ps($parems['password'],$SecretKey);
        if($passwd!=$Password)
        {
            $Common->msg('密码错误！',10002);
        }
        $token['id']=$memberId;
        $token['time']=time();
        Session::set('token',$Common->setToken($token));
        Session::set('time',$token['time']);
        Session::set('id',$memberId);
        Session::set('schoolId',$schoolId);
        Session::set('userType',$parems['userType']);

        $Common->msg('登陆成功！',10000);
    }
}
