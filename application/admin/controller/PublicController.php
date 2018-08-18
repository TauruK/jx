<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class PublicController extends Controller{
	
	//登录页
	public function login(){
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'User.login');
			if($result !== true){
				//验证不通过
				$this->error($result);
			}
			//4.调用模型用户数据验证方法，以最终确定此用户在数据库中是否存在
			$re = User::userCheck($post['username'],$post['password']);
			if($re === true){
				//数据库验证通过,登录成功重定向到后台首页
				$this->redirect('/');
			}else{
				//数据与数据库的数据不匹配
				$this->error($re);
			}
		}
		
		return $this->fetch();
	}
	
	//用户退出登录
	public function logout(){
		//删除用户登录session数据
		session('user_id',null);
		session('username',null);
		//重定向到登录页
		$this->redirect('/admin/public/login');
	}
}
?>