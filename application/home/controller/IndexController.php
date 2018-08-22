<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
class IndexController extends Controller{
	
	//首页
	public function index(){
		
		//调用分类模型
		$catModel = new Category();
		
		//首页导航显示
		$catData = $catModel
				->where('is_show','1')
				->where('pid','0')
				->select()
				->toArray();
		
		return $this->fetch('',[
			'catData' => $catData
		]);
	}
	
}
?>