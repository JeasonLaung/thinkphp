<?php
namespace app\api\controller;
use app\api\model\User as UserModel;
use app\api\model\Role as RoleModel;
use think\facade\Session;
class User
{
	public function add($password,$username,$captcha,$role,$way,UserModel $User,RoleModel $Role)
	{
		$role = $Role->get([
			'role'	=>$role,
			'status'=>1,
		]);

		if (!$role) {
			return error(400,'角色异常');
		}
		
		$user = $User->field('id')->get(['username'=>$username]);

		if ($user) {
			return error(400,'用户存在请直接登录！');
		}

		//检查绑定正确性
		$res = \Bind::check(['account'=>$username,'type'=>'register','code'=>$captcha]);
		if($res !== true) {
			return error(400,$res);
		}
		
		//插入表user和role，并取得齐id
		$user_id = $User->insertGetId([
			'password'=>hash_password($password),
			'username'=>$username,
			'role_id' =>$role['id'],
			'create_time'=>now()
		]);
		$role_id = model($role->table)->insertGetId([
			'user_id' =>$user_id,
			'create_time'=>now()
		]);

		//用户信息
		$newUser = [
			'id'=>$user_id,
			'is_admin'=>0,
			'role'=>$role->role,
			'table'=>$role->table
		];
		//注册成功
		//登录行为
		\think\facade\Hook::listen('login',$newUser);
		return success(Session::get(SESSION_ROLE_INFO));
	}

	public function update(){


		//行为
		\think\facade\Hook::listen('register',$user);
	}

	public function find($password,$username,UserModel $User)
	{
		$role_info = Session::get(SESSION_ROLE_INFO);
		//查看角色
		if (!isset($role_info['id'])) {
			$user = $User->alias('u')
				  ->where('username',$username)
				  ->where('password',hash_password($password))
				  ->join('role r','u.role_id=r.id OR ISNULL(u.role_id)')
				  ->field(['u.id','u.is_admin','r.role','r.table'])
				  ->find();
			if (!$user) {
				return error(400,'用户名或密码错误');
			}
			//登录行为
			\think\facade\Hook::listen('login',$user);
			return success(Session::get(SESSION_ROLE_INFO));
		}else{
			return error(400,'已经登录');
		}
	}
}