<?php
namespace app\home\model;
use think\Model;
use think\Db;
class Goods extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "goods_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//按一定规则获取一些商品，$where参数可以是个数组或字符串
	public static function getGoods($where,$order,$num){
			
		//返回查询出的数据
		return $goods = Goods::where($where)
								->order($order)
								->limit($num)
								->select()
								->toArray();
	}
}
?>