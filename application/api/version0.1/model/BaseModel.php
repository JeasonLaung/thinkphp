<?php
namespace app\api\model;

use think\Model;
use app\api\model\City;
use app\api\model\Province;
class BaseModel extends Model
{
	protected $hidden = ['status','is_display'];
	protected $readonly = [
		'create_time',
		'update_time',
		'status',
		'company_id',
		'student_id',
		'hr_id',
		'user_id',
		'id'
	];

	//分析model类名
	public function analysis(){
		$class = get_called_class();
		$arr = explode('\\', get_called_class());
		$end = end($arr);
		return uncamelize($end);
	}

	//软删除
	public function trash($id='')
	{
		$model = $this->analysis();
		return db($model)->where('id',$id)
						 ->where('status',1)
						 ->update(['status'=>0]);
	}
	//回收
	public function recovery($id='')
	{
		$model = $this->analysis();
		return db($model)->where('id',$id)
						 ->where('status',0)
						 ->update(['status'=>1]);
	}

	//添加
	public function add($data='')
	{
		if (!$data) {
			$data = (new \think\Request)->except($this->readonly);
		}
		$data['create_time'] = now();
		return $this->insertGetId($data);
	}

	//编辑
	public function edit($id='',$data='')
	{
		if (!$data) {
			$data = (new \think\Request)->put();
		}
		$res = $this->where('id',$id)
					->where('status',1)
					->update($data);
		return $id;
	}

	//获取城市名称
	public function getCityAttr($value='')
	{
		$City = new City;
		return $City->get($value)['name'];
	}

	//获取省份名称
	public function getProvinceAttr($value='')
	{
		$Province = new Province;
		return $Province->get($value)['name'];
	}

	// public function setCityAttr($value='',City $City)
	// {
	// 	return $City->get(['name'=>$value])['id'] | '';
	// }
	// public function setProvinceAttr($value='',Province $Province)
	// {
	// 	return $Province->get(['name'=>$value])['id'] | '';
	// }
}