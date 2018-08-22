<?php
namespace app\admin\controller;
use app\admin\model\Goods;
use app\admin\model\Category;
use app\admin\model\Type;
use app\admin\model\Attribute;
class GoodsController extends CommonController{
	
	//商品列表页
	public function index(){
		
		//调用模型查询所有商品
		$goodsModel = new Goods();
		$goodsData = $goodsModel
							->alias('t1')
							->field('t1.*,t2.cat_name')
							->join('jx_category t2','t1.cat_id=t2.cat_id','left')
							->select();
		
		return $this->fetch('',[
			'goodsData' => $goodsData
		]);
	}
	
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
			/**************图上上传处理*******************/
			//1.【调用模型图片上传处理函数】
			$goods_img = $goodsModel->getOirImgPath();
			//如果返回的不是一个空数组则表示有图片上传
			if($goods_img){
				//2.将这个数组路径转换为json格式存入数据库
				$post['goods_img'] = json_encode($goods_img);
				//3.【调用生成略缩图处理函数】
				$thumbImg = $goodsModel->createThumb($goods_img);
				//4.将中图数组路径转换为json格式存入数据库
				$post['goods_middle'] = json_encode($thumbImg['middle']);
				//5.将小图数组路径转换为json格式存入数据库
				$post['goods_thumb'] = json_encode($thumbImg['small']);
				//halt($thumbImg);
			}
			/**************图上上传处理*******************/
			
			//save方法返回的是写入的记录数
			if($goodsModel->allowField(true)->save($post)){
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
		//查询所有商品类型
		$typeData = Type::select();
		return $this->fetch('',[
			'cats' => $cats,
			'typeData' => $typeData
		]);
	}

	//ajax动态显示商品类型属性
	public function getTypeAttr(){
		
		//判断是否是ajax请求
		if(request()->isAjax()){
			//接收参数
			$type_id = input('type_id');
			//查询类型所拥有的所有属性
			$attr = Attribute::where("type_id",$type_id)->select()->toArray();
			//将数据转换为json格式返回给ajax
			echo json_encode($attr);
			//dump($attr);
		}
		
	}
	
}
?>