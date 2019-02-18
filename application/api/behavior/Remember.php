<?php 
	
namespace app\api\behavior;
use think\Cookie;

/**
 * 
 */
class Remember
{
	public function run()
	{
		if (session('?'.SESSION_USER_INFO)) {
			return;
		}
		$token = cookie(COOKIE_LOGIN_STATUS);

		if ($token) {
			$user = model('user')->get(['token'=>$token]);
			if (!is_null($user)) {
				if ($user['is_admin']) {
					session(SESSION_USER_INFO,['uid'=>$user['id']]);
					session(SESSION_SUPERADMIN,1);
					return;
				}
				$uid = $user['id'];
				$role_id = $user['role_id'];
				$role = model('role')->get($role_id);
				$role_name = $role->role;
				$table = $role->table;
				$params = [
					'role_id'=>$role_id,
					'uid'=>$uid,
					'table'=>$table,
					'role'=>$role_name,
					'exit'=>false
				];
				behavior('\app\api\behavior\Login',$params);
			}
		}
	}
}