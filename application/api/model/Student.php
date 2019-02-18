<?php
namespace app\api\model;

use think\Model;

class Student extends Model
{
	// protected $readonly = ['id','create_time','update_time','status'];
	public function getOneAll($id)
	{
		return $this->with('educations,works,experiences')
			->get($id);
	}
	public function works()
	{
		return $this->hasMany('student_work');
	}
	// public function labels()
	// {
	// 	return $this->hasMany('student_label');
	// }
	public function educations()
	{
		return $this->hasMany('student_education');
	}
	public function experiences()
	{
		return $this->hasMany('student_experience');
	}
	// public function getDegreeAttr($value='')
	// {
	// 	$arr = ['高中','大专','本科','硕士'];
	// 	if (!$value) {
	// 		return '';
	// 	}
	// 	return $arr[$value-1]; 
	// }


	public function setExpectCityAttr($value='')
	{
		return model('city')->get(['short_name'=>$value])->id;
	}
	// public function getGenderAttr($value='')
	// {
	// 	return $value==1 ? '男' : '女';
	// }
	// public function setGenderAttr($value='')
	// {
	// 	return $value=='男' ? 1 : 2;
	// }
	public function getExpectCityAttr($value='')
	{
		return model('city')->get($value)['short_name'];
	}

	public function getLabelsAttr($value='')
	{
		// $label_arr = json_decode($value,true);
		return model('label')->field('id,name')->all($value);
	}
	public function getLogoAttr($value='')
	{
		return (model('photo')->get($value))['path'];
	}
}