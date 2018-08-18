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
		//新增前钩子
        User::event('before_insert', function ($user) {
        	//添加用户数据入库之前给密码拼接上盐在加密
        	$user['password'] = md5($user['password'].config('password_salt'));
        });
		
		//编辑前钩子
		 User::event('before_update', function ($user) {
		 	//如果密码为空，则表示用户不修改密码,删除这个密码字段
		 	if(empty($user['password'])){
		 		unset($user['password']);
		 	}else{
		 		//密码填了，用户正在进行修改密码操作
		 		//更新用户数据入库之前给密码拼接上盐在加密
        		$user['password'] = md5($user['password'].config('password_salt'));
		 	}
        	
        });
    }
	
	//用户登录验证
	public static function userCheck($username,$password){
		//从数据库中查询用户信息
		$user = User::where('username',$username)->find();
		//判断数据
		if(empty($user)){
			//如果没有查到用户名，$user为null表示没有此用户，返回错误信息
			return '用户名不存在';
		}
		//通过上面的验证，表示用户名存在判断密码是否匹配
		if($user['password'] != md5($password.config('password_salt'))){
			//密码不匹配，返回错误信息
			return '密码错误';
		}
		
		//如果上面的验证都通过允许用户登录,设置用户session数据,并返回true
		session('user_id',$user['user_id']);
		session('username',$user['username']);
		return true;
	}
	
}
?>