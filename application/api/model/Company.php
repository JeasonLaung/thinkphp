<?php
namespace app\api\model;

use think\Model;

class Company extends Model
{	
	protected $hidden = ['status','is_display'];

	public function getCityAttr($value='')
	{
		return (model('city')->get($value))['short_name'];
	}
	public function getProvinceAttr($value='')
	{
		return (model('province')->get($value))['short_name'];
	}


	public function getLogoAttr($value='')
	{
		return (model('photo')->get($value))['path'];
	}

	public function getLabelsAttr($value='')
	{
		return (model('label')->field('name')->all($value));
	}
	
	public function jobs($value='')
	{
		return $this->hasMany('job','company_id')->order('create_time','desc');
	}
}