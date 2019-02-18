<?php
namespace app\api\behavior;

/*
 * 登录行为
 *
 */
use think\facade\Session;
use think\Db;

class Login
{
	protected $hiddenFields;
	
	public function run($user)
	{
		// halt($user);
		$user_id = $user['id'];
		$is_admin = $user['is_admin'];
		$role_name = $user['role'];
		$role_table = $user['table'];

		if ($is_admin === 1) {
			Session::set(SESSION_USER_INFO,['id'=>$user_id,'role'=>'superadmin']);
			Session::set(SESSION_SUPERADMIN,true);
			Session::set(SESSION_ROLE_INFO,['id'=>0]);

		}else{
			//存储用户基本资料
			Session::set(SESSION_USER_INFO,['id'=>$user_id,'role'=>$role_name]);
			//找到对应表格查询角色信息
			$role_info = model($role_table)->where('user_id',$user_id)
										   ->hidden(['status','user_id'])
										   ->find()
										   ->toArray();

			Session::set(SESSION_ROLE_INFO,$role_info);
			// return success($role_info);
			// //记录用户这次一次登录时间
			// model('user')	  ->   where('id',$user_id)
			// 	 		 	  ->   data(['last_login_time'=>now()])
			// 	 		 	  ->   update();
			// //记录角色这次一次登录时间
			// model($role_table)->   where('user_id',$user_id)
			// 	 			  ->   data(['last_login_time'=>now()])
			// 	 		 	  ->   update();
		}

	}
}