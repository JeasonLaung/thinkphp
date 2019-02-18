<?php
namespace app\api\model;

use think\Model;

class StudentWork extends Model
{
	public function getPhotoAttr($value='')
	{
		return model('photo')->field('path')->get($value)->path;
	}
	public function getToAttr($value='')
	{
		if ($value == '0000-00-00' || is_null($value)) {
			return '至今';
		}
		return $value;
	}
}