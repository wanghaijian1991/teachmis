<?php
use think\Route;
// 注册路由到index模块的News控制器的read操作
Route::rule('ceshi/:id','index/index/ceshi');
Route::rule('login/:id','index/login/index');
Route::rule('myOperation','index/myOperation/index');
Route::rule('myOperationinfo/:id','index/myOperation/info');
