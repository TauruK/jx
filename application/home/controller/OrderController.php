<?php
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\Goods;
use app\home\model\Order;
use cart\Cart;
class OrderController extends Controller{
	
	//订单提交页
	public function commitOrder(){
		
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		
		//实例化购物车类
		$cart = new Cart();
		//获取用户购物车商品
		$cartData = $cart->getCartGoods();
		
		
		return $this->fetch('',[
			'cartData' => $cartData
		]);
		
	}
	
	//订单提交处理
	public function orderUpd(){
		//1.判断用户是否登录
		if(!session("member_id")){
			$this->error('请先登录','/home/public/login');
		}
		//判断是否是post请求
		if(request()->isPost()){
			//接收参数
			$post = input('post.');
			//验证器验证数据
			$result = $this->validate($post,'Order.commit');
			if($result !== true){
				$this->error($result);
			}
		/********************构建订单入库所需参数*******************************/
			//实例化购物车类
			$userCart = new Cart();
			//获取用户购物车商品
			$cartData = $userCart->getCartGoods();
			
			$total_price = 0;  //订单总价
			foreach($cartData as $k=>$cart){
				$total_price += ($cart['goodsInfo']['goods_price']+$cart['attr']['attr_total_price'])*$cart['goods_number'];
			}
			
			$post['order_id'] = date('ymdhsi').mt_rand(100000, 999999);  //唯一订单号
			$post['member_id'] = session('member_id');  //用户id
			$post['total_price'] = $total_price;   //订单总价
			
			//数据入库
			//开启事务
			Db::startTrans();
			try{
				$reOrder = Order::create($post);  //订单信息入库
				$data = [];
				foreach($cartData as $k=>$cart){
					$data['order_id'] = $post['order_id'];
					$data['goods_id'] = $cart['goods_id'];
					$data['goods_attr_ids'] = $cart['goods_attr_ids'];
					$data['goods_number'] = $cart['goods_number'];
					$data['goods_price'] = ($cart['goodsInfo']['goods_price']+$cart['attr']['attr_total_price'])*$cart['goods_number'];
					$reGoodsOrder = Db::name('order_goods')->insert($data);  //商品订单入库
					
					//主动抛出异常throw new Exception('2 is not allowed as a parameter');
					if(!$reOrder || !$reGoodsOrder){
						throw new \Exception('提交订单失败');
					}
					
					//更新商品表库存
					$where = [
						'goods_id'=>$cart['goods_id'],
						'goods_number'=>['>=',$cart['goods_number']]
					];
					if(!Goods::where($where)->setDec('goods_number',$cart['goods_number'])){
						throw new \Exception('你购买的商品数大于库存数');
					}
				}
				
				//清空购物车
				$userCart->clearCatr();
				
				// 提交事务
    			Db::commit(); 
				
				//唤起支付宝
				$this->openAlipay($post['order_id'],$total_price);
				
				
			}catch(\Exception $e){
				
				dump($e->getMessage());
				// 回滚事务
   				Db::rollback();	
			}
			
		}
	}


	//唤起支付宝
	public function openAlipay($trade_no,$total_amount,$subject='京西商城',$body=''){
		
		$url = url('/home/order/pagepay');
		$str = <<<AIL
		<form name=alipayment action=$url method="post" target="_blank">
			<input type="hidden" name="WIDout_trade_no" value="$trade_no" />
			<input type="hidden" name="WIDsubject" value="$subject" />
			<input type="hidden" name="WIDtotal_amount" value="$total_amount" />
			<input type="hidden" name="WIDbody" value="$body" />
		</form>
		<script>
			document.getElementsByName('alipayment')[0].submit();
		</script>
AIL;
	echo $str;
	}
	
	//支付方法
	public function pagepay(){
		require EXTEND_PATH.'alipay/pagepay/pagepay.php';
	}
	
	//支付宝同步回调地址
	public function return_url(){
		
		/* *
		 * 功能：支付宝页面跳转同步通知页面
		 * 版本：2.0
		 * 修改日期：2017-05-01
		 * 说明：
		 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
		
		 *************************页面功能说明*************************
		 * 该页面可在本机电脑测试
		 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
		 */
		require_once(EXTEND_PATH."alipay/config.php");
		require_once EXTEND_PATH.'alipay/pagepay/service/AlipayTradeService.php';
		
		
		$arr=$_GET;
		
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);
		
		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
		
			//商户订单号
			$out_trade_no = htmlspecialchars($_GET['out_trade_no']);
		
			//支付宝交易号
			$trade_no = htmlspecialchars($_GET['trade_no']);
				
			//var_dump($arr);
		
			//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
			//更新订单支付状态
			Db::name('order')->where('order_id',$out_trade_no)->update(['ali_order_id'=>$trade_no,'pay_status'=>1]);
			
			
			return $this->fetch();
		}
		else {
		    //验证失败
		    $this->error("验证失败");
		}
		
	}

	//异步回调
	public function notify_url(){
		//项目上线时必须用这个方法
	}

	//订单列表
	public function orderList(){
		
		$member_id = session('member_id');
		//查询用户订单
		$order = Order::where('member_id',$member_id)->order('id desc')->select()->toArray();
		
		//halt($order);
		
		return $this->fetch('',[
			'order'=>$order
		]);
	}
	
	//未支付订单支付
	public function orderPay(){
		
		$order_id=input('order_id');
		$order = Order::where('order_id',$order_id)->find();
		//唤起支付宝
		$this->openAlipay($order['order_id'],$order['total_price']);
		
	}

}
?>