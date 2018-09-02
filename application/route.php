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
//测试控制器路由
Route::get('home/test/test/:id/:name','home/test/test');
//网站首页
Route::get('/','home/index/index');
//路由分组,前台路由
Route::group('home',function(){
	//前台首页路由
	Route::get('index/index','home/index/index');
	
	//public控制器路由
	Route::any('public/register','home/public/register');
	Route::any('public/login','home/public/login');
	Route::get('public/logout','home/public/logout');
	Route::any('public/qq','home/public/qq');                //唤起qq
	Route::any('public/qq_login','home/public/qq_login');   //qq登录回调函数
	
	//短信发送路由
	Route::get('public/sendSMS','home/public/sendSMS');
	//找回密码路由
	Route::any('public/find','home/public/find');
	//密码重置
	Route::any('public/change/:member_id/:token/:time','home/public/change');
	
	//分类列表页路由
	Route::any('list/index','home/list/index');
	//商品详情页
	Route::any('goods/details','home/goods/details');
	//在商品页里加入购物车
	Route::any('goods/addGoodsToCart','home/goods/addGoodsToCart');
	
	//购物车相关路由
	Route::any('cart/clearing','home/cart/clearing');
	Route::any('cart/incGoodsNum','home/cart/incGoodsNum');
	Route::any('cart/decGoodsNum','home/cart/decGoodsNum');
	Route::any('cart/inputGoodsNum','home/cart/inputGoodsNum');
	Route::any('cart/cartGoodsDel','home/cart/cartGoodsDel');
	Route::any('cart/clearGoodsCart','home/cart/clearGoodsCart');
	
	//订单路由
	Route::any('order/commitOrder','home/order/commitOrder');
	Route::any('order/orderUpd','home/order/orderUpd');
	Route::any('order/openAlipay','home/order/openAlipay');
	Route::any('order/pagepay','home/order/pagepay');
	Route::any('order/return_url','home/order/return_url');
	Route::any('order/notify_url','home/order/notify_url');
	Route::any('order/orderList','home/order/orderList');
	Route::any('order/orderPay','home/order/orderPay');
});

/*******************************************************************************/
//路由分组,后台路由
Route::group('admin',function(){
	//后台首页路由
	Route::get('index/index','admin/index/index');
	Route::get('index/top','admin/index/top');
	Route::get('index/left','admin/index/left');
	Route::get('index/main','admin/index/main');
	
	//后台登录路由
	Route::any('public/login','admin/public/login');
	Route::get('public/logout','admin/public/logout');
	
	//后台用户管理页
	Route::get('user/index','admin/user/index');
	Route::any('user/add','admin/user/add');
	Route::any('user/upd','admin/user/upd');
	Route::get('user/del','admin/user/del');
	
	//权限相关页路由
	Route::get('auth/index','admin/auth/index');
	Route::any('auth/add','admin/auth/add');
	Route::any('auth/upd','admin/auth/upd');
	
	//角色相关路由
	Route::get('role/index','admin/role/index');
	Route::any('role/add','admin/role/add');
	Route::any('role/upd','admin/role/upd');
	
	/**********************商品管理相关路由***********************************/
	//商品类型路由
	Route::get('type/index','admin/type/index');
	Route::any('type/add','admin/type/add');
	Route::any('type/upd','admin/type/upd');
	
	//类型属性路由
	Route::get('attribute/index','admin/attribute/index');
	Route::any('attribute/add','admin/attribute/add');
	Route::any('attribute/upd','admin/attribute/upd');
	
	//商品分类路由
	Route::get('category/index','admin/category/index');
	Route::any('category/add','admin/category/add');
	Route::any('category/upd','admin/category/upd');
	
	//商品添加路由
	Route::get('goods/index','admin/goods/index');
	Route::any('goods/add','admin/goods/add');
	Route::any('goods/upd','admin/goods/upd');
	Route::any('goods/getTypeAttr','admin/goods/getTypeAttr');  //ajax动态显示商品类型路由
	
	//订单路由
	Route::get('order/index','admin/order/index');
	Route::any('order/updWuliu','admin/order/updWuliu');
	Route::any('order/showWuliu','admin/order/showWuliu');
	
});
