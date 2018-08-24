<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//关闭变量未定义错误报告
//error_reporting(0);




/**
  * 发送模板短信
  * @param to 手机号码集合,用英文逗号分开
  * @param datas 内容数据 格式为数组 例如：array('Marry','Alon')，如不需替换请填 null
  * @param $tempId 模板Id,测试应用和未上线应用使用测试模板请填写1，正式应用上线后填写已申请审核通过的模板ID
  */       
function sendTemplateSMS($to,$datas,$tempId){

	include_once(EXTEND_PATH."SMS/CCPRestSmsSDK.php");
	
	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
	$accountSid= '8aaf07086541761801655c353bfc1181';
	
	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
	$accountToken= '3e7f7e39ef764409ac85ff287a8311fc';
	
	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
	$appId='8aaf07086541761801655c353c571188';
	
	//请求地址
	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
	//生产环境（用户应用上线使用）：app.cloopen.com
	$serverIP='app.cloopen.com';
	
	
	//请求端口，生产环境和沙盒环境一致
	$serverPort='8883';
	
	//REST版本号，在官网文档REST介绍中获得。
	$softVersion='2013-12-26';
     // 初始化REST SDK
     $rest = new REST($serverIP,$serverPort,$softVersion);
     $rest->setAccount($accountSid,$accountToken);
     $rest->setAppId($appId);
    
     //返回信息服务响应结果
     return $rest->sendTemplateSMS($to,$datas,$tempId);
}

//发送邮箱函数
/*
 * array|string $toEmail 收件人邮箱
 * string $title 发送标题
 * string $content 发送内容 
 */
function sendEmail($toEmail,$title,$content){
	// 实例化
	include EXTEND_PATH."SendEmail/class.phpmailer.php";
	$pm = new PHPMailer();
	// 服务器相关信息
	$pm->Host = 'smtp.163.com'; // SMTP服务器
	$pm->IsSMTP(); // 设置使用SMTP服务器发送邮件
	$pm->SMTPAuth = true; // 需要SMTP身份认证
	$pm->Username = '15625591879'; // 登录SMTP服务器的用户名，邮箱@前面一串字符
	$pm->Password = 'qwer568635172'; //授权码 登录SMTP服务器的密码
	
	// 发件人信息
	$pm->From = '15625591879@163.com'; //自己的邮箱
	$pm->FromName = '京西商城'; // 发件人昵称，名字可以随便取
	
	// 收件人信息
	//判断邮箱是否是批量发送
	if(is_array($toEmail)){
		//传入的邮箱是个数组，批量发送
		foreach($toEmail as $email){
			$pm->AddAddress($email, '官人'); // 设置收件人邮箱和昵称，昵称名字随便取
		}
	}else{
		//传入的邮箱是个字符串，发送单条
		$pm->AddAddress($toEmail, '官人'); // 设置收件人邮箱和昵称，昵称名字随便取
	}
	

	$pm->CharSet = 'utf-8'; // 内容编码
	$pm->Subject = $title; // 邮件标题
	$pm->MsgHTML($content); // 邮件内容
	
	//var_dump($pm->Send()); //成功返回true
	// 发送邮件
	if($pm->Send()){
	   return true;
	}else {
	   return $pm->ErrorInfo;
	}
}
