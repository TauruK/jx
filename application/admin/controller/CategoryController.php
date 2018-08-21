<?php
namespace app\admin\controller;
use app\admin\model\Category;
class CategoryController extends CommonController{
	
	//分类列表页
	public function index(){
		
		//调用模型查询分类
		$catModel = new Category();
		$catData = $catModel->select()->toArray();
		$cats = $catModel->recursionCat($catData);
		
		return $this->fetch('',[
			'cats'=>$cats
		]);
	}
	
	//添加分类
	public function add(){
		
		//调用分类模型
		$catModel = new Category();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Category.add',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			//save方法返回的是写入的记录数
			if($catModel->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('添加成功了哟~','/admin/category/index');
			}else{
				$this->error('添加失败了哟~');
			}
		}
		
	
		//查询所有分类
		$catData = $catModel->select()->toArray();
		$cats = $catModel->recursionCat($catData);
		
		
		return $this->fetch('',[
			'cats'=>$cats
		]);
	}
	
	//编辑分类页
	public function upd(){
		
		//调用分类模型
		$catModel = new Category();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Category.add',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			if($catModel->update($post)){
				//更新成功
				$this->success('编辑成功了哟~','/admin/category/index');
			}else{
				$this->error('编辑失败了哟~');
			}
		}
		
		
		//接收参数
		$cat_id = input('cat_id');
		
		//查询所有分类
		$catData = $catModel->select()->toArray();
		$cats = $catModel->recursionCat($catData);
		
		$cat = $cats[$cat_id];  //获取接收参数指定的分类数据
		
		return $this->fetch('',[
			'cat'=>$cat,
			'cats'=>$cats
		]);
	}
}
?>