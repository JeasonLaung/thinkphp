<?php 
namespace app\api\validate;
use think\Validate;
class User extends Validate
{
	protected $rule = [
		'username|用户名' => 'require',//|length:5,30
		'password|密码' => 'require',

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
		'register' => ['username','password'],
	];

}
