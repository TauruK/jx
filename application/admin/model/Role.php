<?php
namespace app\admin\model;
use think\Model;
class Role extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "role_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//在模型类的init方法里面统一注册模型事件
	protected static function init(){
		
		//新增前钩子
        Role::event('before_insert', function ($role) {
        	//数据入库前需要先将，auth_ids_list炸成字符串，post提交过来的是个数组
        	$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
        });
		
		//更新前钩子
        Role::event('before_update', function ($role) {
        	//数据入库前需要先将，auth_ids_list炸成字符串，post提交过来的是个数组
        	$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
        });
	}
	
	//定义一个获取所有权限的静态方法
	
}
?>