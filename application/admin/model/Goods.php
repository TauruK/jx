<?php
namespace app\admin\model;
use think\Model;
use think\Image;
use think\Db;
class Goods extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "goods_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//注册模型事件
	protected static function init(){
		
		//新增前事件
		Goods::event('before_insert',function($goods){
				
			//自动生成商品货号
			$goods['goods_sn'] = 'SN'.date('Ymdhis',time()).rand(1000, 9999);
			//halt($goods['goods_sn']);
		});
		
		
		//新增后事件
		Goods::event('after_insert',function($goods){
				//$goods['goods_id']
			//接收参数
			$attr = input('post.')['attr'];
			$price = input('post.')['price'];
			//循环插入数据
			foreach($attr as $k => $v){
				//判断该数据是否是个数组，如果是则表示为单选属性，否则为唯一属性
				if(is_array($v)){
					foreach($v as $attr_sub => $attr_value){
						//构建新增数据
						$data = [
							'goods_id' => $goods['goods_id'],
							'attr_id' => $k,
							'attr_value' => $attr_value,
							'attr_price' => $price[$k][$attr_sub],
							'create_time' => time(),
							'update_time' => time()
						];
						//数据入库
						Db::name('goods_attr')->insert($data);
						
					}
				}else{
					//如果是唯一属性
					//构建新增数据
					$data = [
						'goods_id' => $goods['goods_id'],
						'attr_id' => $k,
						'attr_value' => $v,
						'create_time' => time(),
						'update_time' => time()
					];
					//数据入库
					Db::name('goods_attr')->insert($data);
				}
			}
		});
		
		/*************************************************/
		
	}
	
	//处理原图上传函数,并返回原图保存路径
	public function getOirImgPath(){
		//定义一个空数组,用于保存原图路径
		$oriImgPath=[];
		//定义上传验证规则
		$check = ['size'=>1024*1024*2,'ext'=>'jpg,png,gif'];
		
		//1.获取表单上传文件
		$file = request()->file('goodsImg');
		//2.判断是否有上传图片
		if($file){
			//3.循环转移图片
			foreach($file as $v){
				// 移动到框架应用根目录/uploads/ 目录下
				$info = $v->validate($check)->move('./upload/goods');
				if($info){
					//转移图片成功，获取上传信息，并将图片路径保存到数组中
					$oriImgPath[]=str_replace('\\', '/', $info->getSaveName());
				}
				
			}
			
		}
		//返回图片路径
		return $oriImgPath;
	}
	
	//生成略缩图处理
	public function createThumb($oriImg){
		
		//定义两个空数组
		$middleImg = [];
		$smallImg = [];
		//判断是否是个空数组
		if($oriImg){
			//循环处理图片,生成中图middle与小图small
			foreach($oriImg as $v){
				//打开图片资源
				$img = Image::open('./upload/goods/'.$v);
				//构建中图保存路径
				$arr = explode('/', $v);
				$mid_imgPath = $arr[0].'/'.'middle_'.$arr[1];   //中图路径
				$small_imgPath = $arr[0].'/'.'small_'.$arr[1];  //小图路径
				
				// 按照原图的比例生成一个最大为350*350的中图缩略图并保存
				$img->thumb(350, 350,2)->save('./upload/goods/'.$mid_imgPath);
				$middleImg[] = $mid_imgPath;
				
				// 按照原图的比例生成一个最大为50*50的小图缩略图并保存
				$img->thumb(50, 50,2)->save('./upload/goods/'.$small_imgPath);
				$smallImg[] = $small_imgPath;
				
			}
		}
		//返回中图与小图的路径
		return [
			'middle'=>$middleImg,
			'small'=>$smallImg
		];
	}
	
}
?>