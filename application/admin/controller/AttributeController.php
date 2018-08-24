<?php
namespace app\admin\controller;
use app\admin\model\Attribute;
use app\admin\model\Type;
class AttributeController extends CommonController{
	
	//类型属性列表
	public function index(){
		
		//判断有没有传类型id，定义一个变量$screen来做筛选条件
		if($type_id = input('type_id')){
			//有传type_id 则 按type_id来查询相应的属性
			$screen = "t1.type_id={$type_id}";
		}else{
			//没有传type_id则查询出所有属性
			$screen = 1;
		}
		//调用商品类型属性模型
		$attributeModel = new Attribute();
		$attributes = $attributeModel
							->field('t1.*,t2.type_name')
							->alias('t1')
							->where($screen)
							->join('jx_type t2','t1.type_id=t2.type_id','left')
							->paginate(100);
		
		return $this->fetch('',[
			'attributes' => $attributes
		]);
	}
	
	//类型属性添加页
	public function add(){
		
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			//判断用户选择的输入方式
			if($post['attr_input_type']==1){
				//选择了列表选择，要求必须输入可选属性值，需要验证是否有输入
				$result = $this->validate($post,'Attribute.add_2',[],true);
			}else{
				//选择了手工输入
				$result = $this->validate($post,'Attribute.add_1',[],true);
			}
			
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证通过，数据入库
			$attributeModel = new Attribute();
			//save方法返回的是写入的记录数
			if($attributeModel->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('添加属性成功了哟~','/admin/attribute/index');
			}else{
				$this->error('添加属性失败了哟~');
			}
		}
		
		//查询所有类型
		$types = Type::select();
		
		return $this->fetch('',[
			'types'=>$types
		]);
	}
	
	//编辑商品类型属性页
	public function upd(){
		
		//调用商品类型属性模型
		$attributeModel = new Attribute();
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据,注意编辑页验证的时候一定要在表单里加上表主键id,可以通过隐藏域传
			//判断用户选择的输入方式
			if($post['attr_input_type']==1){
				//选择了列表选择，要求必须输入可选属性值，需要验证是否有输入
				$result = $this->validate($post,'Attribute.upd_2');
			}else{
				//选择了手工输入
				$result = $this->validate($post,'Attribute.upd_1');
			}
			
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error($result);
			}
			
			//4.验证通过，数据入库
			//save方法返回的是写入的记录数
			if($attributeModel->update($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('编辑属性成功了哟~','/admin/attribute/index');
			}else{
				$this->error('编辑属性失败了哟~');
			}
		}
		
		//接收参数
		$attr_id = input('attr_id');
		$attr = $attributeModel->find($attr_id);
		
		//查询所有类型
		$types = Type::select();
		
		return $this->fetch('',[
			'attr'=>$attr,
			'types'=>$types
		]);
	}
	
	
	
}
?>