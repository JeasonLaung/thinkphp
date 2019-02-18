<?php 

namespace app\api\validate;
use think\Validate;

/**
 * 
 */
class Add extends Validate
{
	//规则
	protected $rule = [
		'work_name|作品名' => 'require|max:60',
		'work_link|链接' => 'require|regex:^(http(s)?:\/\/)?www\.kujiale\.com\/(.+)',
		'photo|图片' => 'require',
		// 'from|开始时间'=>sprintf('require|date|before:%s',now()),
		'from|开始时间'=>'require|date|checkDate',
		// 'to|结束时间'=>'date|checkDate:to',
		// 'email' => 'email',
		// 'logo|头像' => 'file|fileSize:50|fileExt:jpg,png,gif'

		//education
		'school_name|学校名' => 'require|max:50',
		// 'degree'=>'require'
		'major_name|专业名'=>'require|max:50',
		'description|描述'=>'require|min:15|max:500',

		//experience
		'company_name|公司名' =>'require|max:50',
		'position_name|职位名'=>'require|max:50',

	];

	//自定义消息
	protected $message = [
		'work_link.regex' => '链接需要是<b>酷家乐</b>的作品链接'
		// 'name.require' => '名称必须',
		// 'name.max' => '名称最多不能超过25个字符',
		// 'age.number' => '年龄必须是数字',
		// 'age.between' => '年龄只能在1-120之间',
		// 'email' => '邮箱格式错误',
	];

	//场景
	protected $scene = [
		'student_work' => ['work_name','work_link','photo','from'],
		'student_education'=>['school_name','major_name','from','description'],

		'student_experience'=>['company_name','position_name','from','description'],

	];
	// public function sceneStudentWork()
	// {
	// 	return $this->only(['work_name','work_link','photo','from']);
	// }

	// 自定义验证规则
	protected function checkDate($value,$rule,$data=[])
	{
		if (array_key_exists('to', $data)){
			return $data['to'] >= $data['from'] ? true : '开始时间不得晚于结束时间';
		}
		return $value < now() ? true : '开始日期不能早于今天';
	}

}