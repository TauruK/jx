<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
class IndexController extends Controller{
	
	//首页
	public function index(){
		
		//调用分类模型
		$catModel = new Category();
		//调用商品模型
		$goodsModel = new Goods();
		
		//首页导航显示
		$catData = $catModel->getNavCat();
		
		//侧边栏所有分类显示,这个方法返回的是个重组分类后的数组
		$catAll = $catModel->getCatAll();
		
		//按一定规则获取具体的一些商品用作展示
		$is_sale = $goodsModel->getGoods(['is_sale'=>1], 'goods_price', 5);
		$is_hot = $goodsModel->getGoods(['is_hot'=>1], 'goods_number', 3);
		$is_best = $goodsModel->getGoods(['is_best'=>1], 'goods_price desc', 5);
		$is_new = $goodsModel->getGoods(['is_new'=>1], 'goods_price desc', 5);
		//halt($is_sale);
		return $this->fetch('',[
			'catData' => $catData,
			'catInfo' => $catAll['catInfo'],
			'catMap' => $catAll['catMap'],
			'is_sale' => $is_sale,
			'is_hot' => $is_hot,
			'is_best' => $is_best,
			'is_new' => $is_new,
		]);
	}
	
}
?>