<?php
namespace app\api\controller;
use think\facade\Session;
class Verify
{
	public function register($email='')
	{
		$captcha = mt_rand(100000,999999);
		$verify = [
			'type'     => 'register',
			'account'  => $email,
			'captcha'  => $captcha,
			'dead_time'=> time()+60,
		];
		Session::set(SESSION_VERIFY,$verify);

		$res = \Email\Email::captcha($email,$captcha);
		if ($res === true) {
			return success(false,'发送成功');
		}else{
			return error(400,'发送失败');
		}

	}
}