<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

//路由定义
use think\Route;

/************rule分组批量路由注册(推荐用这种)***********************/
//网站首页
Route::get('/','admin/index/index');
//路由分组,后台路由
Route::group('admin',function(){
	//后台首页路由
	Route::get('index/index','admin/index/index');
	Route::get('index/top','admin/index/top');
	Route::get('index/left','admin/index/left');
	Route::get('index/main','admin/index/main');
	
	//后台登录路由
	Route::any('public/login','admin/public/login');
	
	//后台用户管理页
	Route::any('user/add','admin/user/add');
});
