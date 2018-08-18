<?php
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
class UserController extends Controller{
	
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
}
?>