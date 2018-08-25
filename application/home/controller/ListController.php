<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
class ListController extends Controller{
	
	//列表页首
	public function index(){
		
		//调用分类模型
		$catModel = new Category();
		//接收cat_id参数
		$cat_id = input("cat_id");
		
		/******************面包屑分类导航****************************************************/
		
		//查询所有分类
		$cats = $catModel->select()->toArray();
		//根据当前分类查找父分类
		$parentCats = $catModel->getParentCats($cats,$cat_id);
		
		//列表页左侧分类菜单,先使用奇营技巧重组所有分类
		$catInfo = [];
		foreach($cats as $v){
			$catInfo[$v['cat_id']] = $v;
		}
		
		$catMap = [];
		foreach($cats as $v){
			$catMap[$v['pid']][] = $v['cat_id'];
		}
		
		//查询出当前分类及其子子分类下的所有商品
		$childCats = $catModel->getChildCats($cats,$cat_id);
		$childCats[] = (int)$cat_id;   //将自身分类也加进去
		
		//查询商品
		$where = [
			'cat_id'=>['in',$childCats],
			'is_delete'=>0,
			'is_sale'=>1
		];
		$goods = Goods::where($where)->select()->toArray();
		//halt($goods);
		
		
		return $this->fetch('',[
			'parentCats' => $parentCats,
			'catInfo' => $catInfo,
			'catMap' => $catMap,
			'goods' => $goods
		]);
	}
	
	
}
?>