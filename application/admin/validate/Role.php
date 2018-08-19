<?php
namespace app\admin\validate;
use think\Validate;
//权限验证器
class Role extends Validate{
	//定义验证规则
	protected $rule = [
		'role_name' => 'require|min:3|max:30|unique:Auth'
	];
	
	//定义错误信息
	protected $message = [
		//权限验证错误信息
		'role_name.require' => '权限名不能为空',
		'role_name.min' => '权限名不能小于3个字符',
		'role_name.max' => '权限名不能大于30个字符',
		'role_name.unique' => '权限名已存在'
		
	];
	
	//定义场景
	protected $scene = [
		'add' => ['role_name','pid','auth_c','auth_a']
	];
}
?>