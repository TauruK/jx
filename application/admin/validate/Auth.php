<?php
namespace app\admin\validate;
use think\Validate;
//权限验证器
class Auth extends Validate{
	//定义验证规则
	protected $rule = [
		'auth_name' => 'require|min:3|max:30|unique:Auth',
		'auth_c' => 'require',
		'auth_a' => 'require',
		'pid' => 'require'
	];
	
	//定义错误信息
	protected $message = [
		//权限验证错误信息
		'auth_name.require' => '权限名不能为空',
		'auth_name.min' => '权限名不能小于3个字符',
		'auth_name.max' => '权限名不能大于30个字符',
		'auth_name.unique' => '权限名已存在',
		'pid.require' => '请填选择一个父级权限',
		'auth_c.require' => '请填写控制器名',
		'auth_a.require' => '请填写方法名'
		
	];
	
	//定义场景
	protected $scene = [
		'add_1' => ['auth_name','pid','auth_c','auth_a'],
		'add_2' => ['auth_name','pid']
	];
}
?>