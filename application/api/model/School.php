<?php
namespace app\api\model;

use think\Model;

class School extends Model
{
	protected $hidden = ['status','is_display'];

	// public function getCityAttr($value='')
	// {
	// 	return (model('city')->get($value))['short_name'];
	// }
	// public function getProvinceAttr($value='')
	// {
	// 	return (model('province')->get($value))['short_name'];
	// }
	// public $major_num = 123;

	public function getPhotosAttr($value='')
	{
		return model('photo')->field('path')->all($value);
	}
	public function getLogoAttr($value='')
	{
		return (model('photo')->get($value))['path'];
	}

	public function getLabelsAttr($value='')
	{
		return (model('label')->field('name')->all($value));
	}
}