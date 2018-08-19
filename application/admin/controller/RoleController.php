<?php
namespace app\admin\controller;
use app\admin\model\Role;
use app\admin\model\Auth;
class RoleController extends CommonController{
	
	//角色添加页
	public function add(){
		
		//调用角色模型
		$roleModel = new Role();
		
		//1.判断是否是POST数据
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证post数据
			$result = $this->validate($post,'Role.add');
			if($result !==true){
				//验证不通过
				$this->error($result);
			}
			//4.执行新增操作
			if($roleModel->save($post)){
				//新增成功
				$this->success('新增权限成功','/admin/role/index');
			}else{
				//新增失败
				$this->error('新增权限失败');
			}
			
		}
		
		
		//调用模型
		$authModel = new Auth();
		$authData = $authModel->select()->toArray();
		//使用奇妙技巧重构数组
		//技巧1.先将权限数组下标用权限的auth_id替换了
		$auth_1 = [];
		foreach($authData as $v){
			$auth_1[$v['auth_id']] = $v;
		}
		//技巧2.将权限数组根据pid也就是父级权限id进行分组重组
		$auth_2 = [];
		foreach($authData as $v){
			$auth_2[$v['pid']][] = $v['auth_id'];
		}
		
		
		return $this->fetch('',[
			'auth_1'=>$auth_1,
			'auth_2'=>$auth_2
		]);
	}
}
?>