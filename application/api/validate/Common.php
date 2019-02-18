<?php 

namespace app\api\validate;
use think\Validate;

/**
 * 
 */
class Common extends Validate
{
	//规则
	protected $rule = [
		// 'name' => 'require|max:25',
		// 'email' => 'email',
		// 'logo|头像' => 'file|fileSize:50|fileExt:jpg,png,gif'
	];

	//自定义消息
	protected $message = [
		// 'name.require' => '名称必须',
		// 'name.max' => '名称最多不能超过25个字符',
		// 'age.number' => '年龄必须是数字',
		// 'age.between' => '年龄只能在1-120之间',
		// 'email' => '邮箱格式错误',
	];

	//场景
	protected $scene = [
		// 'uploadLogo' => ['logo'],
	];

	// 自定义验证规则
	protected function checkName($value,$rule,$data=[])
	{
		return $rule == $value ? true : '名称错误';
	}

}