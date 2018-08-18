<?php
namespace app\admin\controller;
use think\Controller;
class PublicController extends Controller{
	
	//登录页
	public function login(){
		
		
		return $this->fetch();
	}
}
?>