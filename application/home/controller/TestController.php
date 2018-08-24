<?php
namespace app\home\controller;
use think\Controller;
class TestController extends Controller{
	
	//测试方法
	public function test($id,$name){
		dump($id);
		dump($name);
		//dump($k);
		return request()->domain().url('/home/public/change/5');
	}
}
?>