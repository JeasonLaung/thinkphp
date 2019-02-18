<?php 

namespace app\api\validate;
use think\Validate;

/**
 * 
 */
class Hr extends Validate
{
	//规则
	protected $rule = [
		'name'=>''
	];

	//自定义消息
	protected $message = [
		// 'work_link.regex' => '链接需要是<b>酷家乐</b>的作品链接'
	];

	//场景
	protected $scene = [
		// 'student_work' => ['work_name','work_link','photo','from'],

	];
	// public function sceneStudentWork()
	// {
	// 	return $this->only(['work_name','work_link','photo','from']);
	// }

	// 自定义验证规则
	protected function checkDate($value,$rule,$data=[])
	{
		
	}

}