<?php
namespace app\admin\controller;
class IndexController extends CommonController{
	
	//后台首页
	public function index(){
		
		
		return $this->fetch();
	}
	//top
	public function top(){
		
		
		return $this->fetch();
	}
	//left
	public function left(){
		
		$auth_info = session('user_auth_info');
		$auth = session('user_auth');
		//halt($auth);
		return $this->fetch('',[
			'auth_info'=>$auth_info,
			'auth'=>$auth
		]);
	}
	//main
	public function main(){
		
		
		return $this->fetch();
	}
	
}
?>