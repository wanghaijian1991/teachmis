<?php
namespace app\index\model;

use think\Db;
use think\Model;

class Member extends Model
{
    protected $table = 'user';

    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法
        parent::initialize();
        //TODO:自定义的初始化
    }

    //查询用户信息
    public function getMemberInfo($parems)
    {
        $where['usersName']=$parems['user'];
        return Db::name('users')->where($where)->find();
    }
}
?>
