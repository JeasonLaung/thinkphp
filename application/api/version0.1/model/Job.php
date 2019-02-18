<?php
namespace app\api\model;
use app\api\model\BaseModel;
// use think\Model;

class Job extends BaseModel
{
	
	public function companyInfo($value='')
	{
		return $this->belongsTo('Company');
	}
	
	public function hrInfo($value='')
	{
		return $this->belongsTo('hr');
	}
	// public function trash($id='')
	// {
		
	// }
	// public function recycle($id='')
	// {
	// 	# code...
	// }
}