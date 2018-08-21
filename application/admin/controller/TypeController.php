<?php
namespace app\admin\controller;
use app\admin\model\Type;
class TypeController extends CommonController{
	
	//类型列表页
	public function index(){
		
		//查询所有类型数据
		$types = Type::alias('t1')
					->field('t1.*,count(t2.type_id) attr_num')
					->join('jx_attribute t2','t1.type_id=t2.type_id','left')
					->group('t1.type_id')
					->paginate(10);
		
		return $this->fetch('',[
			'types'=>$types
		]);
	}
	
	
	
	//添加类型页
	public function add(){
		
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Type.add',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			$typeModel = new Type();
			//save方法返回的是写入的记录数
			if($typeModel->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('添加类型成功了哟~','/admin/type/index');
			}else{
				$this->error('添加类型失败了哟~');
			}
		}
		
		return $this->fetch();
	}
	
	
	//类型编辑页
	public function upd(){
		
		//调用商品类型模型
		$typeModel = new Type();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Type.upd',[],true);
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			$typeModel = new Type();
			//save方法返回的是写入的记录数
			if($typeModel->update($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('编辑类型成功了哟~','/admin/type/index');
			}else{
				$this->error('编辑类型失败了哟~');
			}
		}
		
	
		//接收参数
		$type_id = input('type_id');
		$type = $typeModel->find($type_id);
		
		return $this->fetch('',[
			'type'=>$type
		]);
	}
}
?>