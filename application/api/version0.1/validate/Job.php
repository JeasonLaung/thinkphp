<?php 
namespace app\api\validate;

class Job extends BaseValidate
{
	protected $rule = [
		'title|岗位名' => 'require',
		'description|工作职责'=>'require|min:15',
	];

	protected $message = [
		// 'username.require' => '',
		// 'title' => '',
		'description.min' => '工作职责至少填写15字，让求职者更了解该工作',
	];

	protected $scene = [
		'add' => ['title','description'],
		// 'register' => [	'username',	'password','captcha',],
		// 'bindEmail'=>['email'],
		// 'bindMobile'=>['mobile'],
	];

}
