<?php
namespace app\admin\validate;
use think\Validate;
//商品类型属性验证器
class Attribute extends Validate{
	//定义验证规则
	protected $rule = [
		'attr_name' => 'require|max:30|unique:Attribute',
		'type_id' => 'require',
		'attr_values' => 'require'
	];
	
	//定义错误信息
	protected $message = [
		//商品类型验证错误信息
		'attr_name.require' => '类型属性名不能为空',
		'attr_name.max' => '类型属性名不能大于30个字符',
		'attr_name.unique' => '类型属性名已存在',
		'type_id.require' => '请选择一个类型',
		'attr_values.require' => '你选择了列表选择，至少需要输入一个可选属性值'
		
	];
	
	//定义场景
	protected $scene = [
		'add_1' => ['attr_name','type_id'],
		'add_2' => ['attr_name','type_id','attr_values'],
		'upd_1' => ['attr_name','type_id'],
		'upd_2' => ['attr_name','type_id','attr_values']
	];
}
?>