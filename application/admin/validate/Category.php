<?php
namespace app\admin\validate;
use think\Validate;
//分类验证器
class Category extends Validate{
	//定义验证规则
	protected $rule = [
		'cat_name' => 'require|max:30|unique:Category',
		'pid' => 'require'
	];
	
	//定义错误信息
	protected $message = [
		//商品类型验证错误信息
		'cat_name.require' => '分类名不能为空',
		'cat_name.max' => '分类名不能大于30个字符',
		'cat_name.unique' => '分类名已存在',
		'pid.require' => '请选择一个分类'
		
	];
	
	//定义场景
	protected $scene = [
		'add' => ['cat_name','pid'],
		'upd' => ['type_name']
	];
}
?>