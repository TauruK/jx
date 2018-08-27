<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
use \cart\Cart;
use think\Db;
class CartController extends Controller{
	
	//购物车结算列表
	public function clearing(){
		
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//实例化购物车类
		$cart = new Cart();
		//获取用户购物车商品
		$cartData = $cart->getCartGoods();
		
		//dump($cartData);
		
		return $this->fetch('',[
			'cartData' => $cartData
		]);
	}
	
	//购物车商品数量添加
	public function incGoodsNum(){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			//halt(input('goods_attr_ids'));
			//实例化购物车模型
			$cart = new Cart();
			//增加购物车商品数量
			$re = $cart->inc($goods_id,$goods_attr_ids);
			if(!$re){
				echo json_encode([
					'code'=>-1,
					'msg'=>'商品增加失败'
				]);
				exit;
			}
			echo json_encode([
					'code'=>200,
					'msg'=>'商品增加成功'
			]);
		}
	}
	
	//购物车商品数量减少
	public function decGoodsNum(){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			//halt(input('goods_attr_ids'));
			//实例化购物车模型
			$cart = new Cart();
			//增加购物车商品数量
			$re = $cart->dec($goods_id,$goods_attr_ids);
			if(!$re){
				echo json_encode([
					'code'=>-1,
					'msg'=>'商品减少失败'
				]);
				exit;
			}
			echo json_encode([
					'code'=>200,
					'msg'=>'商品减少成功'
			]);
		}
	}
	
	//直接输入购物车商品数量
	public function inputGoodsNum(){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			$goods_number = input('goods_number');
			//halt(input('goods_attr_ids'));
			//实例化购物车模型
			$cart = new Cart();
			//增加购物车商品数量
			$re = $cart->inputNum($goods_id,$goods_attr_ids,$goods_number);
			if(!$re){
				echo json_encode([
					'code'=>-1,
					'msg'=>'商品数量更新失败'
				]);
				exit;
			}
			echo json_encode([
					'code'=>200,
					'msg'=>'商品数量更新成功'
			]);
		}
	}
	
	//购物车商品删除
	public function cartGoodsDel($goods_id,$goods_attr_ids){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			//halt(input('goods_attr_ids'));
			//实例化购物车模型
			$cart = new Cart();
			//增加购物车商品数量
			$re = $cart->del($goods_id,$goods_attr_ids);
			if(!$re){
				echo json_encode([
					'code'=>-1,
					'msg'=>'商品删除失败'
				]);
				exit;
			}
			echo json_encode([
					'code'=>200,
					'msg'=>'商品删除成功'
			]);
		}
	}
	
	//清空购物车
	public function clearGoodsCart(){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//2.判断是否是ajax请求
		if(request()->isAjax()){
			//3.接收参数
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			
			//实例化购物车模型
			$cart = new Cart();
			//增加购物车商品数量
			$re = $cart->clearCatr();
			if(!$re){
				echo json_encode([
					'code'=>-1,
					'msg'=>'清空购物车失败'
				]);
				exit;
			}
			echo json_encode([
					'code'=>200,
					'msg'=>'清空购物车成功'
			]);
		}
	}
}
?>