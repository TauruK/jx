<?php
namespace app\admin\controller;
use app\admin\model\User;
class UserController extends CommonController{
	
	//用户列表页
	public function index(){
		
		//调用模型
		$userModel = new User();
		$users = $userModel->paginate(2);
		
		return $this->fetch('',[
			'users' => $users
		]);
	}
	
	//用户添加页
	public function add(){
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'User.add',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			$userModel = new User();
			//save方法返回的是写入的记录数
			if($userModel->allowField(true)->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('添加成功了哟~','/admin/user/index');
			}else{
				$this->error('添加用户失败了哟~');
			}
		}
		
		return $this->fetch();
	}
	
	//用户编辑页
	public function upd(){
		
		//调用模型
		$userModel = new User();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			//如果密码和确认密码都没填就表示不修改密码，不需要验证
			if(!empty($post['password']) || !empty($post['repassword'])){
				//如果密码和确认密码其中有填一个就表示要验证
				$result = $this->validate($post,'User.upd',[],true);
				if($result !== true){
					//验证不通过
					$this->error(implode(',', $result));
				}
			}
			//验证通过，数据入库
			if($userModel->allowField(true)->isUpdate(true)->save($post)){
				//更新成功
				$this->success('编辑成功','/admin/user/index');
			}else{
				//更新失败
				$this->error('编辑失败');
			}
		}
		
		//接收get参数
		$user_id = input('user_id');
		//查询用户数据
		$user = $userModel->find($user_id);
		return $this->fetch('',[
			'user' => $user
		]);
	}
	
	//删除用户
	public function del(){
		
		//接收参数
		$user_id = input('user_id');
		//调用模型
		$userModel = new User();
		if($userModel->destroy($user_id)){
			//删除成功
			$this->success('删除成功哟~','/admin/user/index');
		}else{
			//删除失败
			$this->error('嘿嘿，删除失败');
		}
	}
}
?>