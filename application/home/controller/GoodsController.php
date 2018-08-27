<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
use \cart\Cart;
use think\Db;
class GoodsController extends Controller{
	
	//商品详情页
	public function details(){
		
		//接收参数
		$goods_id = input('goods_id');
		
		//查询商品信息
		$goods = Goods::find($goods_id);
		
		//详情页面包屑
		$catModel = new Category();
		$cats = $catModel->select()->toArray();
		$parentCats = $catModel->getParentCats($cats, $goods['cat_id']);
		
		//分配三种图给模版
		$goods_l = json_decode($goods['goods_img']);
		$goods_m = json_decode($goods['goods_middle']);
		$goods_s = json_decode($goods['goods_thumb']);
		
/******************************商品单选属性*********************************************************/
		//查询商品单选属性信息,这里使用了原生查询
		$sql = "select t1.*,t2.attr_name from jx_goods_attr t1
							LEFT JOIN jx_attribute t2
							ON t1.attr_id=t2.attr_id
							WHERE t1.goods_id = {$goods_id} and t2.attr_type=1";
		$GoodsAttr = Db::query($sql);
		//将查询出的相同类型的单选属性进行分组
		$goodsAttrGroup = [];
		foreach($GoodsAttr as $v){
			$goodsAttrGroup[$v['attr_id']][] = $v;
		}
		
/**********************************查询商品的唯一属性*****************************************************/
		$sql = "select t1.*,t2.attr_name from jx_goods_attr t1
							LEFT JOIN jx_attribute t2
							ON t1.attr_id=t2.attr_id
							WHERE t1.goods_id = {$goods_id} and t2.attr_type=0";
		$GoodsAttrOnly = Db::query($sql);
		
/*******************************商品浏览记录功能***************************************************************/
		//当用户每次访问商品详情页时就记住一个浏览的商品id
		Goods::addGoodsHistory($goods_id);
		//查询浏览记录中的商品
		$history = cookie('goods_history');
		$history_str = implode(',', $history);
		$sql = "select * from jx_goods where goods_id in ({$history_str}) 
							ORDER BY FIELD(goods_id,{$history_str})";
							
		$goodsHistory = Db::query($sql);
		//halt($goodsHistory);
		
		//halt($GoodsAttrOnly);
		
		
		return $this->fetch('',[
			'parentCats' => $parentCats,      //面包宵分类
			'goods' => $goods,                //商品信息
			'goods_l' => $goods_l,           //商品小图
			'goods_m' => $goods_m,           //商品中图
			'goods_s' => $goods_s,            //商品大图
			'goodsAttrGroup' => $goodsAttrGroup,  //商品单选属性组
			'GoodsAttrOnly' => $GoodsAttrOnly,     //商品唯一属性
			'goodsHistory' => $goodsHistory        //商品浏览记录
		]);
	}
	
	
	//商品加入购物车功能
	public function addGoodsToCart(){
		//1.判断用户是否登录
		if(!session("member_id")){
			echo json_encode([
				'code'=>-1,
				'msg'=>'登录后才能加入购物车哦~'
			]);
			exit;
		}
	
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			$goods_number = input('goods_number');
			//实例化购物车模型
			$cart = new Cart();
			//将商品添加到购物车
			$re = $cart->add($goods_id,$goods_attr_ids,$goods_number);
			if($re){
				echo json_encode([
					'code'=>200,
					'msg'=>'加入购物车成功'
				]);
				exit;
			}else{
				echo json_encode([
					'code'=>-2,
					'msg'=>'加入购物车失败,请稍后在试'
				]);
				exit;
			}
		}
		
	}
	
	
}
?>