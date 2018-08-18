<?php
namespace app\admin\controller;
use app\admin\model\Auth;
class AuthController extends CommonController{
	
	//权限添加页
	public function add(){
		
		//1.判断是否是POST数据
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证post数据
			//注意，这里的添加场景有两种情况，默认为添加场景1验证所有数据，一种是权限为顶级时不用验证控制器名和方法名
			$addScene = 'Auth.add_1';
			if($post['pid']==0){
				$addScene = 'Auth.add_2';
			}
			$result = $this->validate($post,$addScene);
			if($result !==true){
				//验证不通过
				$this->error($result);
			}
			//4.执行新增操作
			if($authModel->save($post)){
				//新增成功
				$this->success('新增权限成功','/admin/auth/index');
			}else{
				//新增失败
				$this->error('新增权限失败');
			}
			
		}
		//调用权限模型
		$authModel = new Auth();
		//获取所有权限数据
		$authData = $authModel->select()->toArray();
		//调用模型递归重组权限方法
		$auth = $authModel->recursionAuth($authData);
		
		return $this->fetch('',[
			'auth' => $auth
		]);
	}
}
?>