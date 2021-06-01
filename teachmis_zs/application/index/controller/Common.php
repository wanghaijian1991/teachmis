<?php
namespace app\index\controller;

use think\Controller;
use think\Request;
use think\Session;
use app\index\model\MenuInfo;

class Common extends Controller
{
    public function _initialize()
    {
        parent::_initialize();
        $session=Session::get();
        if(isset($session) && isset($session['token'])) {
            $MenuInfo = new MenuInfo();
            $mailinfo = $MenuInfo->mainMenu();
            $this->assign('menu_list', $mailinfo);
        }
    }

    /**
     * 验证是否登录
    */
    public function checkLogin()
    {
        $session=Session::get();
        $request=Request::instance();
        if($request->action()!='login')
        {
            if(isset($session) && isset($session['token']))
            {
                $token=$this->setToken($session);
                if($session['token']!=$token)
                {
                    Session::clear();
                    header('location:' . __APP__ . '/login');
                }
            }else{
                $Login=new \app\index\controller\Login();
                $Login->index();
                //header('location:/login/1');
            }
        }
        return $session;
    }

    /**
     * 密码加密方式
    */
    public function md5ps($password,$key){
        return md5(md5($password)."teachmis".$key);
    }

    /**
     * json返回提示
    */
    public function msg($message,$status=1) {
        //@header("Content-type:application/json,text/html,*/*");
        echo json_encode(array('status' => $status, 'message' => $message));
        exit;
    }
    
    /**
     * token生成器
    */
    public function setToken($parems)
    {
        return md5(md5($parems['id'].'teachmis').$parems['time']);
    }
}
