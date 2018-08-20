<?php
namespace app\admin\validate;
use think\Validate;
//User验证器
class User extends Validate{
	//定义验证规则
	protected $rule = [
		'username' => 'require|min:3|max:30|unique:User',
		'role_id' => 'require',
		'password' => 'require',
		'repassword' => 'require|confirm:password',
		'captcha' => 'require|captcha'
	];
	
	//定义错误信息
	protected $message = [
		//用户名验证错误信息
		'username.require' => '用户名不能为空',
		'username.min' => '用户名不能小于3个字符',
		'username.max' => '用户名不能大于30个字符',
		'username.unique' => '用户名已存在',
		'role_id' => '请为用户选择一个角色',
		//密码验证错误信息
		'password.require' => '密码不能为空',
		'repassword.require' => '确认密码不能为空',
		'repassword.confirm' => '两次密码不一致',
		'captcha.require' => '请填写验证码',
		'captcha.captcha' => '验证码错误'
		
	];
	
	//定义场景
	protected $scene = [
		'add' => ['role_id','username','password','repassword'],
		'upd' => ['role_id','password','repassword'=>'require|confirm:password'],
		'login' => ['username'=>'require|min:3|max:30','password','captcha']
	];
}
?>