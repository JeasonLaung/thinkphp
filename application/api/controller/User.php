<?php 

namespace app\api\controller;
use think\Controller;
use think\facade\Session;
use think\facade\Cookie;
use app\api\model\User as UserModel;
/**
 * 
 */
class User extends Controller
{
	public function getRole()
	{
		$role_name = (Session::get(SESSION_ROLE_INFO))['role'];
		return success('I\'m a '.$role_name);
	}

	public function getRoleInfo()
	{
		if (!cookie('?'.COOKIE_LOGIN_STATUS)) {
			$user_info = Session::get(SESSION_USER_INFO);
			$uid = $user_info['uid'];
			$res = model('user')->field('token,token_dead_time')->get(['id'=>$uid]);
			Cookie::set(COOKIE_LOGIN_STATUS,$res['token'],$res['token_dead_time']);
		}
		$role_info = Session::get(SESSION_ROLE_INFO);
		$role_name = $role_info['role'];
		$table = $role_info['table'];
		$rid = $role_info['rid'];
		$a_role = model($table)->get($rid);
		$a_role['role'] = $role_name;
		return success($a_role);
	}

	public function login($password,$username,UserModel $User)
	{
		$user = $User->where('password',hash('sha256', $password))
			->where('username',$username)
			->find();
		if (is_null($user)) {
			return error(400,'用户名或密码不正确');
		}
		if ($user['status'] == 0) {
			return error(400,'该用户已被锁定');
		}
		if ($user['is_admin'] == 1) {
			session(SESSION_SUPERADMIN,1);
			session(SESSION_USER_INFO,['uid'=>$user['id']]);
			return success(false,'  '.$user['username']);
		}
		$uid = $user['id'];
		$role_id = $user['role_id'];
		$role = model('role')->get($role_id);
		$table = $role->table;
		$role_name = $role->role;

		$role_info = behavior('app\api\behavior\Login',['uid'=>$uid,'role_id'=>$role_id,'table'=>$table,'role'=>$role_name]);
		success($role_info,'登录成功');
	}

	public function register($role,$username,$password,$captcha,UserModel $User)
	{
		//检查用户存在
		$res = $User->field('id')
					->get(['username'=>$username]);
		if (null !== $res) {
			return error(400,'用户已存在，请直接登录。');
		}

		//检查验证码
		$verify = Session::get(SESSION_VERIFY);

		if(!$verify) {
			return error(400,'请重新发送验证码！');
		}
		$compare_verify = [
			'account'=>$username,
			'type'=>'register',
			'captcha'=>$captcha,
		];

		foreach (array_keys($verify) as $key) {
			if ($key == 'dead_time') {
				if(now() < $verify[$key]) {
					continue;
				}
			}
			if ($verify[$key] != $compare_verify[$key]) {
				return error(400,'验证码不正确');
			}
		}

		$role = model('role')->get(['alias'=>$role,'status'=>1]);
		if (is_null($role)) {
			return error(400,'角色异常');
		}
		$table = $role['table'];
		$role_name = $role['role'];
		$role_id = $role['id'];

		//生成user
		$user_data = [
			'username' => $username,
			'password' =>hash('sha256', $password),
			'create_time' => now(),
			'role_id'     => $role_id
		];
		//user_id
		$uid = $User->insertGetId($user_data);

		if (!$uid) {
			return error(400,'用户注册异常');
		}

		//生成role
		$role_data = [
			'user_id' => $uid,
			'create_time'=>now()
		];
		$rid = model($table)->insertGetId($role_data);

		if (!$rid) {
			return error(400,'用户注册异常');
		}

		Session::delete(SESSION_VERIFY);

		$role_info = behavior('app\api\behavior\Login',['uid'=>$uid,'role_id'=>$role_id,'table'=>$table,'role'=>$role_name]);
	}


}