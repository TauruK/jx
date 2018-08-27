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

	//添加商品浏览记录功能
	public static function addGoodsHistory($goods_id){
		
		//1.先从cookie中获取商品id,判断cookie中是否有数据
		$history = cookie('goods_history')?cookie('goods_history'):[];
		
		//2.在数组开头插入一个或多个单元
		array_unshift($history,$goods_id);
		//3.过滤重复值
		$history = array_unique($history);
		//4.判断数组元素是否大于5
		if(count($history)>5){
			//弹出数组最后一个单元（出栈）
			array_pop($history);
		}
		
		//5.将最新浏览记录存入cookie
		cookie('goods_history',$history,3600*24*7);
		//halt(cookie('goods_history'));
		
	}
	

}
?>