<?php 

namespace app\api\controller;
use think\Controller;
use think\facade\Request;
/**
 * 
 */
class Index extends Controller
{
	public function index()
	{
		return $this->request->post();
		// $compare_role = [
		// 	1 => 'student',
		// 	2 => 'hr',
		// 	3 => 'school_admin'
		// ];
		// return ;
		// return Request::file('file')->move('../uploads');
		echo hash('sha256', '123456');
		return view('Index/test');
	}
}