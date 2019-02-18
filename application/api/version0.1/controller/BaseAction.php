<?php
namespace app\api\controller;

abstract class BaseAction extends \think\Controller
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
		$data = model($this->model)->get($id);
		if (empty($data)) {
			return error(false);
		}
		return success($data);
	}

	public function update($id)
	{
		$handle = model($this->model)->get($id);
		if (empty($handle)) {
			return error(400);
		}
		$data = $this->request->put();
		$res = $handle->data($data)->save();

		return success(model($this->model)->get($id));
		return success();
	}
}