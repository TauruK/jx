<?php
namespace app\home\validate;
use think\Validate;
//前台会员验证器
class Order extends Validate{
	//定义验证规则
	protected $rule = [
		'receiver' => 'require',
		'address' => 'require',
		'phone' => 'require',
		'zcode' => 'require'
	];
	
	//定义错误信息
	protected $message = [
		
		'receiver.require' => '请填写收货人',
		'address.require' => '请填写收货地址',
		'phone.require' => '请填写手机号',
		'zcode.require' => '请填写邮编'
		
	];
	
	//定义场景
	protected $scene = [
		'commit' => ['receiver','address','phone','zcode']
	];
}
?>