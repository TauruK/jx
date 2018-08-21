<?php
namespace app\admin\validate;
use think\Validate;
//权限验证器
class Type extends Validate{
	//定义验证规则
	protected $rule = [
		'type_name' => 'require|max:30|unique:Type'
	];
	
	//定义错误信息
	protected $message = [
		//商品类型验证错误信息
		'type_name.require' => '类型名不能为空',
		'type_name.max' => '类型名不能大于30个字符',
		'type_name.unique' => '类型名已存在'
		
	];
	
	//定义场景
	protected $scene = [
		'add' => ['type_name'],
		'upd' => ['type_name']
	];
}
?>