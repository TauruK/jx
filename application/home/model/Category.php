<?php
namespace app\home\model;
use think\Model;
class Category extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "cat_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//递归重组排序查询出来的分类方法
	public function recursionCat($catData,$pid=0,$level=0){
		static $newCat = [];   //定义一个静态变量用于保存重组的权限
		foreach($catData as $k => $v){
			if($v['pid']==$pid){
				$v['level']=$level;
				$newCat[$v['cat_id']]=$v;   //优化，以分类的id------cat_id做为新数组的下标,这样可以不用连表就能获取到父分类名
				unset($catData[$k]);   //递归优化，把找到的分类删除了
				//递归重组
				$this->recursionCat($catData,$v['cat_id'],$level+1);
			}
		}
		//返回重组后的数据
		return $newCat;
	}
	
}
?>