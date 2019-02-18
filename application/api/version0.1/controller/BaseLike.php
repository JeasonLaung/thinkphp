<?php
namespace app\api\controller;

class BaseLike extends \think\Controller
{
	protected $beforeActionList = [
		'analysis'
	];
	protected $model = '';
	public function analysis(){
		$arr = explode('\\', get_called_class());
		$end = end($arr);
		$this->model = strtolower($end);
	}
	public function get($id)
	{

	}
}