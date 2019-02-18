<?php 

namespace app\api\behavior;
use think\facade\Session;
use think\facade\Cookie;

/**
 * 
 */
class Login
{
	//[$rold_id,$role,$user_id]
	public function run ($user=['role_id','uid','table','role',['exit']])
	{
		list($role_id,$uid,$table,$role) = [
			$user['role_id'],
			$user['uid'],
			$user['table'],
			$user['role'],
		];
		if (array_key_exists('exit', $user)) {
			$exit = false;
		}else{
			$exit = true;
		}

		$a_role = model($table)->field('*')->get(['user_id'=>$uid]);
		$status = $a_role['status'];
		$rid = $a_role['id'];

		$token = hash('sha256', $uid.$rid.now());
		model('user')->where('id',$uid)->limit('1')->update(['token'=>$token,'token_dead_time'=>date('Y-m-d H:i:s',time()+COOKIE_DEAD_TIME)]);

		//2019-01-26
		Cookie::set(COOKIE_LOGIN_STATUS,$token,COOKIE_DEAD_TIME);

		Session::set(SESSION_USER_INFO,['uid'=>$uid]);
		Session::set(SESSION_ROLE_INFO,[
			'role'=>$role,
			'table'=>$table,
			'rid'=>$rid,
			'status'=>$status,
		]);

		$a_role['role'] = $role;
		if ($exit) {
			return success($a_role,false,true);
		}
	}
}