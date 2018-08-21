<?php
namespace app\admin\model;
use think\Model;
class Goods extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "goods_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//注册模型事件
	protected static function init(){
		
		//新增前事件
		Goods::event('before_insert',function($goods){
				
			//自动生成商品货号
			$goods['goods_sn'] = 'SN'.date('Ymdhis',time()).rand(1000, 9999);
			//halt($goods['goods_sn']);
		});
		
	}
	
}
?>