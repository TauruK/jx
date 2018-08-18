<?php
namespace app\admin\model;
use think\Model;
class User extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "user_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//在模型类的init方法里面统一注册模型事件
	protected static function init(){
        User::event('before_insert', function ($user) {
        	//添加用户数据入库之前给密码拼接上盐在加密
        	$user['password'] = md5($user['password'].config('password_salt'));
        });
    }
	
}
?>