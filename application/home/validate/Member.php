<?php
namespace app\home\validate;
use think\Validate;
//前台会员验证器
class Member extends Validate{
	//定义验证规则
	protected $rule = [
		'username' => 'require|unique:Member|regex:/^\w{3,16}$/',
		'password' => 'require|regex:/^\w{6,18}$/',
		'repassword' => 'require|confirm:password',
		'email' => 'require|unique:Member|regex:/^\w+@(?:\w+\.)+\w+$/',
		'phone' => 'require|unique:Member|regex:/^1[3-9]\d{9}$/',
		'phoneCode' => 'require',                       //手机验证码
		'captcha_register' => 'require|captcha:1',
		'captcha_login' => 'require|captcha:2',
	];
	
	//定义错误信息
	protected $message = [
		//用户名
		'username.require' => '用户名不能为空',
		'username.unique' => '用户名已存在',
		'username.regex' => '用户名非法',
		//密码
		'password.require' => '请填写密码',
		'password.regex' => '密码非法',
		'repassword.require' => '请填写确认密码',
		'repassword.confirm' => '两次密码不一致',
		//邮箱
		'email.require' => '请填写邮箱',
		'email.regex' => '邮箱非法',
		'email.unique' => '此邮箱已注册,请换个邮箱注册',
		//手机
		'phone.require' => '请填写手机号',
		'phone.regex' => '非法手机号',
		'phone.unique' => '此手机号已注册',
		'phoneCode.require' => '请填写手机验证码',
		//验证码
		'captcha_register.require' => '请填写验证码',
		'captcha_register.captcha' => '验证码错误',
		'captcha_login.require' => '请填写验证码',
		'captcha_login.captcha' => '验证码错误',
		
	];
	
	//定义场景
	protected $scene = [
		'register' => ['username','password','repassword','email','phone','phoneCode','captcha_register'],
		'login' => ['username'=>'require|regex:/^\w{3,16}$/','password','captcha_login'],
		'email_find' => ['email' => 'require|regex:/^\w+@(?:\w+\.)+\w+$/'],
		'change' => ['password','repassword']
	];
}
?>