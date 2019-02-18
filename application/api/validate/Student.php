<?php 

namespace app\api\validate;
use think\Validate;

/**
 * 
 */
class Student extends Validate
{
	//规则
	// 'name,degree,birthday,gender,experience'
	protected $rule = [
		//base
		'name|名称' => 'require',
		'degree|学历'=>'require|regex:[0-6]',
		'birthday|生日'  =>'require|date',
		'experience|经验' =>'require|regex:[0-6]',
		'gender|性别' =>'require|regex:[0-2]',

		//expect
		'expect_city|期待工作城市'=>'require',
		'expect_job|期待工作'=>'require',
		'expect_salary|期待薪酬'=>'require|regex:\d+k_\d+k',

		//add_label
		'label_name|标签名'=>'require',
		//labels
		'labels|标签'=>'require',
		//del_label
		// 'label_id|标签ID'=>'require',

	];

	//自定义消息
	protected $message = [
		'salary.regex' => '期待薪酬格式不正确'
	];

	//场景
	protected $scene = [
		'base' => ['name','degree','birthday','gender','experience'],

		'expect' => ['expect_city','expect_salary','expect_job'],
		'labels' => ['labels'],
		'addLabel' => ['label_name'],

	];

	// 自定义验证规则
	protected function checkName($value,$rule,$data=[])
	{
		return $rule == $value ? true : '名称错误';
	}

}