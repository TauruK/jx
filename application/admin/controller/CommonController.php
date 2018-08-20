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
		
		//权限防翻墙
		//1.先获取用户权限信息，并重组为数组形式
		$authInfo = session('user_auth_info');
		$auth = [];
		foreach($authInfo as $v){
			$auth[] = strtolower($v['auth_c'].'/'.$v['auth_a']);
		}
		//2.获取当前访问的控制器与方法名
		$nowVisit = strtolower(request()->controller().'/'.request()->action());
		//3.判断当前访问是否是index控制器，对index控制器放行
		if(strtolower(request()->controller()) == 'index'){
			return true;
		}
		//4.判断是否在访问权限内
		if(!in_array($nowVisit, $auth)){
			//$this->error('你没有权限哦','/admin/index/index');
			//如果不在访问权限内
			$this->error('你没有权限哦');
		}
		//dump($nowVisit);
	}
}
?>