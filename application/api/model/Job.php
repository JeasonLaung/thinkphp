<?php
namespace app\api\model;

use think\Model;

class Job extends Model
{
	protected $hidden = ['status','is_display'];

	public function getCityAttr($value='')
	{
		if (is_null($value)) {
			return '';
		}
		return (model('city')->get($value))['short_name'];
	}
	public function getProvinceAttr($value='')
	{

		return (model('province')->get($value))['short_name'];
	}
	public function getDegreeAttr($value='')
	{
		$arr = ['高中','大专','本科','硕士'];
		if (!$value) {
			return '学历不限';
		}
		return $arr[$value-1]; 
	}
	public function getMinDegreeAttr($value='')
	{
		$arr = ['高中','大专','本科','硕士'];
		if (!$value) {
			return '';
		}
		return $arr[$value-1]."及以上";
	}
	public function getExperienceAttr($value='')
	{
		// $arr = ['高中','大专','本科','硕士'];
		$arr = ["1年以下","1到3年","3到5年","5年-10年","10年以上"];
		if (!$value) {
			return '经验不限';
		}
		return $arr[$value-1]."经验";
	}
	public function getLabelsAttr($value='')
	{
		$arr = json_decode($value);
		return model('label')->field('name')->whereIn('id',$arr)->select();
		$arr = ["1年以下","1到3年","3到5年","5年-10年","10年以上"];
		if (!$value) {
			return '经验不限';
		}
		return $arr[$value-1]."经验";
	}

	public function toCount()
	{
		return $this->count('id');
	}

	//公司简要信息
	public function company()
	{
		return $this->belongsTo('company');
	}
}