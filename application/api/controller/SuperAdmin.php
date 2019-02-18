<?php 

namespace app\api\controller;
use think\facade\Session;
use think\facade\Cookie;

/**
 * 
 */
class SuperAdmin extends \think\Controller
{
	
	protected $beforeActionList = [
		'isSuperAdmin'
	];



	protected function isSuperAdmin()
	{
		$is_super_admin = Session::get(SESSION_SUPERADMIN);
		if (!$is_super_admin) {
			return error(400,false,true,true);
		}
	}

	public function confirmCompany($id='')
	{
		$hr_company = model('hr')->alias('h')->join('company c','h.company_id = c.id')->get(['c.id'=>$id]);
		// dump($res);
		if (is_null($hr_company)) {
			return error(400,'hr and company not relation');
		}
		$res = model('company')->alias('c')->join('hr h','h.company_id = c.id')->where(['c.id'=>$id])->update(['c.status'=>1,'h.status'=>1]);
		// dump($res);
		// echo $hr_company->getLastSql();
		if ($res) {
			return success();
		}
		return error(false);
	}

}


