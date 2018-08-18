<?php
namespace app\admin\controller;
use think\Controller;
//公共控制器
class CommonController extends Controller{
	
	//重写父类控制器初始化方法,用于实现一些公共操作
	public function _initialize(){
		//这里实现一个防翻墙功能
		//判断用户登录session数据是否存在，不存在则未登录
		if(!session('user_id')){
			$this->redirect('/admin/public/login');
		}
	}
}
?>