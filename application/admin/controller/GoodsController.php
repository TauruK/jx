<?php
namespace app\admin\controller;
use app\admin\model\Goods;
use app\admin\model\Category;
class GoodsController extends CommonController{
	
	
	
	//商品添加页
	public function add(){
		
		//调用商品模型
		$goodsModel = new Goods();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Goods.add',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			//save方法返回的是写入的记录数
			if($goodsModel->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('添加成功了哟~','/admin/goods/index');
			}else{
				$this->error('添加失败了哟~');
			}
		}
		
		//调用分类模型，查询所有分类
		$catModel = new Category();
		$catData = $catModel->select()->toArray();
		$cats = $catModel->recursionCat($catData);
		
		return $this->fetch('',[
			'cats' => $cats
		]);
	}
	
}
?>