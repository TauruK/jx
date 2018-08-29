<?php
namespace app\admin\controller;
use app\admin\model\Order;
class OrderController extends CommonController{
	
	//订单列表
	public function index(){
		
		//调用订单模型
		$orderModel = new Order();
		$orders = $orderModel->order('id desc')->paginate(10);
		
		
		return $this->fetch('',[
			'orders'=>$orders
		]);
	}
	
	//分配物流
	public function updWuliu(){
		
		$order_id = input('order_id');
		//判断是否是post请求
		if(request()->isPost()){
			//接收参数
			$post = input('post.');
			$post['send_status']=1;   //设置物流为已发货状态
			//更新物流信息
			if(Order::where('order_id',$post['order_id'])->update($post)){
				$this->success('分配成功','/admin/order/index');
			}else{
				$this->errro('分配失败');
			}
		}
		
		return $this->fetch('',[
			'order_id' => $order_id
		]);
	}
	
	public function showWuliu(){
		
		//判断是否是ajax请求
		if(request()->isAjax()){
			//接收参数
			$com = input('get.com');   //物流公司
			$nu = input('get.nu');   //运单号
			$key = '9d37bc6b0a41e6fe';  //物流接口秘钥
			$url = "http://www.kuaidi100.com/applyurl?key={$key}&com={$com}&nu={$nu}&show=0";
			//返回信息给ajax
			echo file_get_contents($url);
		}
		
	}
	
}
?>