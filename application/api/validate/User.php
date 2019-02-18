<?php 

namespace app\api\validate;
use think\Validate;

/**
 * 
 */
class User extends Validate
{
	//规则
	protected $rule = [
		'email|邮箱' => 'require|email',
		'username|用户名'=>'require',
		'password|密码'  =>'require',
		'captcha|验证码' =>'require'
	];

	//自定义消息
	protected $message = [
	];

	//场景
	protected $scene = [
		'verify' => ['email'],
		'login' => ['username','password'],
		'register' => ['username','password','captcha'],
	];

	// 自定义验证规则
	protected function checkName($value,$rule,$data=[])
	{
		return $rule == $value ? true : '名称错误';
	}

}