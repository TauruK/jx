<?php
namespace app\home\model;
use think\Model;
class Member extends Model{
	// 数据表主键 复合主键使用数组定义 不设置则自动获取
    protected $pk = "member_id";
	// 是否需要自动写入时间戳 如果设置为字符串 则表示时间字段的类型
    protected $autoWriteTimestamp = "true";
	
	//在模型类的init方法里面统一注册模型事件
	protected static function init(){
		
		//新增注册用户前钩子
        Member::event('before_insert', function ($member) {
        	//密码加密
        	$member['password'] = md5($member['password'].config('password_salt'));
        });
		
		/*********************新增注册用户前钩子*****************/
		
		//新增注册用户后钩子
        Member::event('after_insert', function ($member) {
        	
			//将用户信息保存到session用于记录用户的登录状态
			session('member_id',$member['member_id']);
			session('member_username',$member['username']);
        });
		
		/*********************新增注册用户前钩子*****************/
	
	}
	
	//用户登录信息验证
	public static function memberLoginCheck($username,$password){
	
		//查询出用户信息
		$member = Member::where('username',$username)->find();
		//用户名不匹配
		if(!$member){
			return '用户名不存在';
		}
		//用户名匹配，继续验证密码是否匹配
		if($member['password'] != md5($password.config('password_salt')) ){
			return '密码错误';
		}
		//用户名和密码都通过验证
		//将用户信息保存到session用于记录用户的登录状态
		session('member_id',$member['member_id']);
		session('member_username',$member['username']);
		return true;
	}
		
	//验证一个邮箱是否存在数据库中
	public static function is_email($email){
		
		return Member::where('email',$email)->find();
	}
}
?>