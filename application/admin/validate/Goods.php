<?php
namespace app\admin\validate;
use think\Validate;
//商品验证器
class Goods extends Validate{
	//定义验证规则
	protected $rule = [
		'goods_name' => 'require|max:30|unique:Goods',
		'cat_id' => 'require',   //分类
		'goods_price' => 'require|gt:0',  //价格,价格必填，必须要大于0
		'goods_number' => 'require|egt:0' //库存，必填，必须大于等于0
	];
	
	//定义错误信息
	protected $message = [
		//商品验证错误信息
		'goods_name.require' => '商品名不能为空',
		'goods_name.max' => '商品名不能大于30个字符',
		'goods_name.unique' => '商品名已存在',
		'cat_id.require' => '请选择一个分类',
		'goods_price.require' => '请输入价格',
		'goods_price.gt' => '别逗哦，价格必须大于0',
		'goods_number.require' => '请输入库存',
		'goods_number.egt' => '别皮，库存不能小于0'
		
	];
	
	//定义场景
	protected $scene = [
		'add' => ['goods_name','cat_id','goods_price','goods_number'],
		'upd' => ['goods_name','cat_id','goods_price','goods_number'],
	];
}
?>