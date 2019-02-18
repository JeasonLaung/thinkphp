<?php 
namespace app\api\validate;

class User extends BaseValidate
{
	protected $rule = [
		'username|用户名' => 'require',//|length:5,30
		'password|密码' => 'require',
		'captcha|验证码'=>'require',
		'email|邮箱'=>'require|email',
		'mobile|手机'=>'require|mobile',
	];

	protected $message = [
		// 'username.require' => '',
		'name.max' => '名称最多不能超过25个字符',
		'age.number' => '年龄必须是数字',
		'age.between' => '年龄只能在1-120之间',
		'email' => '邮箱格式错误',
	];

	protected $scene = [
		'login' => ['username','password'],
		'register' => [	'username',	'password','captcha',],
		'bindEmail'=>['email'],
		'bindMobile'=>['mobile'],
	];
	// public function sceneRegister()
	// {

	// 	return $this->only(['username','password','captcha']);
	// }

}
