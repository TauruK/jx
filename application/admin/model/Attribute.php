<?php
namespace app\admin\model;
use think\Model;
class Attribute extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "attr_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	
	
}
?>