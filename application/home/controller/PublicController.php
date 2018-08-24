<?php
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;
class PublicController extends Controller{
	
	//登录功能
	public function login(){
		
		//1.判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//3.验证数据
			$result = $this->validate($post,'Member.login',[],true);
			
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//4.验证用户信息在数据库中是否存在,验证通过返回为true
			$loginInfo = Member::memberLoginCheck($post['username'],$post['password']);
			if($loginInfo === true){
				//登录成功
				$this->success('登录成功哟~','/');
			}else{
				//验证不通过
				$this->error($loginInfo);
			}
		}
		
		
		return $this->fetch();
	}
	/*****************************登录功能 end******************************/
	
	//注册功能
	public function register(){
		
		//判断是否是post提交
		if(request()->isPost()){
			//2.接收post数据
			$post = input('post.');
			//将手机验证码拼接【手机号】在拼接密码盐加密匹配，以保证此验证码是用户手机接收
			$phoneCode = md5($post['phone'].$post['phoneCode'].config('password_salt'));
			//halt(cookie('phoneCode1'));
			
			//3.验证数据
			$result = $this->validate($post,'Member.register',[],true);
			
			if($result !== true){
				//验证不通过,并输出错误信息
				$this->error(implode(',', $result));
			}
			
			//1)判断手机验证码是否失效
			if(!cookie('phoneCode')){
				$this->error('官人，手机验证码失效了哦~');
			}
			//2)判断用户输入的手机验证码与存入cookie中的验证码是否一致
			if($phoneCode != cookie('phoneCode')){
				$this->error('官人，手机验证码错误了哦~');
			}
			
			//4.验证通过，数据入库
			$memberModel = new Member();
			//save方法返回的是写入的记录数
			if($member = $memberModel->allowField(true)->save($post)){
				//入库成功,入库前在前钩子里对密码做了加密操作
				$this->success('注册成功了哟~','/home/index/index');
			}else{
				$this->error('注册失败了哟~');
			}
		}
		
		
		return $this->fetch();
	}
	/**************************注册功能   end*************************************/
	
	
	//退出登录功能
	public function logout(){
		
		//退出登录，清空用户登录信息session
		session('member_id',null);
		session('member_username',null);
		//重定向登录页
		$this->redirect('/home/public/login');
	}
	
	/*************************接收ajax请求发送短信验证码***************************/
	public function sendSMS(){
		//接收请求,判断是否是ajax请求
		if(request()->isAjax()){
			//接收手机号
			$phone = input('phone');
			//设置发送随机码
			$phoneCode = mt_rand(1000, 9999);
			
			//调用发送验证码函数
			//*result = sendTemplateSMS("13800000000" ,array('6532','5'),"1");
			//手机号码，替换内容数组，模板ID
			$result = sendTemplateSMS($phone,array($phoneCode,5),"1");
			//判断信息发送状态
			if($result->statusCode=='000000'){
				//短信发送成功
				$phoneCode = md5($phone.$phoneCode.config('password_salt'));
				//将手机验证码拼接【手机号】在拼接加密盐存入cookie----注一定要拼接手机号，保证此验证码是这个手机号接收的
				cookie('phoneCode',$phoneCode,300);  //此cookie 5分钟内有效
				echo json_encode([
					'code' => 200,
					'msg' => '短信验证码发送成功！'
				]);
				exit;
			}else{
				//短信发送失败
				echo json_encode([
					'code' => -1,
					'msg' => '短信验证码发送失败！请稍后在试'
				]);
				exit;
			}
		}
		
	}

/*************************************忘记密码使用邮箱找回密码功能*****************************************/
	public function find(){
		
		//判断是否是Ajax请求
		if(request()->isAjax()){
			
			//接收Ajax数据
			$email = input('email');
			//验证邮箱合法性
			$result = $this->validate(['email'=>$email],'Member.email_find');
			if($result !== true){
				//返回错误信息
				echo json_encode([
					'code' => '-1',
					'msg' => $result
				]);
				exit;
			}
			//验证邮箱是否是已注册邮箱，已注册才给密码找回
			$re = Member::is_email($email);
			if(!$re){
				//邮箱未注册
				echo json_encode([
					'code' => '-2',
					'msg' => '邮箱未注册，不能找回密码'
				]);
				exit;
			}
			
			//通过验证，调用找回密码函数
			//构建邮件发送参数
			$member_id = $re['member_id'];   //用户id
			$toEmail = $email;      //用户邮箱
			$time = time();                 //时间戳
			$title = '京西商城密码找回';
			
			//定义加密令牌：用户id+时间戳+密码盐  进行sha1加密
			$token = sha1($member_id.$time.config('password_salt'));
			
			//构建密码重置url参数 需要用户id
			$content = '点击此链接修改您的密码'.request()->domain().url("/home/public/change/{$member_id}/{$token}/{$time}").'?此链接有效期为2小时，请尽快修改你的密码';
			
			//发送邮件,发送失败会返回一个错误消息
			$re = sendEmail($toEmail,$title,$content);
			if($re !== true){
				//发送失败
				echo json_encode([
					'code' => '-3',
					'msg' => $re
				]);
				exit;
			}
			//更新用户邮件激活字段，表示此邮件可激活
			Member::where('member_id',$member_id)->update(['is_active'=>1]);
			echo json_encode([
					'code' => '200',
					'msg' => '发送成功，请即时修改您的密码'
			]);
			exit;
		}
		
		return $this->fetch();
	}
	
	//密码重置
	public function change($member_id,$token,$time){
		
		//定义邮件有效期
		$due_time = $time+7200;
		//定义匹配加密令牌：用户id+时间戳+密码盐  进行sha1加密
		$newToken = sha1($member_id.$time.config('password_salt'));
		
		//1.如果链接到期
		if($due_time < time()){
			//更新用户邮件激活字段，表示此邮件失效不可激活
			Member::where('member_id',$member_id)->update(['is_active'=>0]);
			$this->error('链接失效','/');
		}
		//2.如果链接未到期，匹配令牌，判断链接是否被篡改
		if($newToken != $token){
			$this->error('非法链接','/');
		}
		//3.查询is_active字段是否为激活状态，此链接只能点击一次,如果为1则用户没有点过此链接，否则用户已点了此链接
		$is_active = Member::alias('is_active')->find($member_id)['is_active'];
		if(!$is_active){
			$this->error('客官你已使用过此链接了哦','/');
		}
		
	/****************************************************************************/
	
		//判断是否是POST请求
		if(request()->isPost()){
			//接收post数据
			$post = input('post.');
			//验证重置密码合法性
			$result = $this->validate($post,'Member.change');
			if($result !== true){
				$this->error($result);
			}
			//重置密码
			$data = [
				'member_id' => $member_id,
				'password' => md5($post['password'].config('password_salt'))
			];
			if(Member::update($data)){
				//更新用户邮件激活字段，表示此邮件已被激活过
				Member::where('member_id',$member_id)->update(['is_active'=>0]);
				$this->success('密码重置成功哟','/home/public/login');
			}else{
				$this->error('密码重置失败，请稍后在试');
			}
		}
		
	
		return $this->fetch();
	}
	
}
?>