<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
//草鸡会员
define('SESSION_SUPERADMIN', 'is_superadmin');

//角色信息   [id=>213,name=>jeason]
define('SESSION_ROLE_INFO', 'role_info');

//用户信息   [id=>213,name=>jeason]
define('SESSION_USER_INFO', 'user_info');

//csrf
define('SESSION_TOKEN', 'access_token');

/**
* 下划线转驼峰
* 思路:
*/
function camelize($uncamelized_words,$separator='_')
{
    $uncamelized_words = $separator. str_replace($separator, " ", strtolower($uncamelized_words));
    return ltrim(str_replace(" ", "", ucwords($uncamelized_words)), $separator );
}

/**
* 驼峰命名转下划线命名
*/
function uncamelize($camelCaps,$separator='_')
{
    return strtolower(preg_replace('/([a-z])([A-Z])/', "$1" . $separator . "$2", $camelCaps));
}

//现在时间
function now(){
	date_default_timezone_set('PRC'); 
	return date('Y-m-d H:i:s');
}

//随机发送给绑定手机或邮箱的验证码
function random_verify($figure=null){
	if (is_null($figure)) {
		return mt_rand(100000,999999);
	}
	return mt_rand((int)('1'.str_repeat('0',$figure-1)),(int)(str_repeat(9,$figure)));
}

//密码hash
function hash_password($str,$compare=false){
	if ($compare) {
		return hash('sha256',$str) == $compare;
	}
	return hash('sha256',$str);
}

//成功输出
function success($data='',$msg='',$exit=false)
{
	$tmp = [];
	$tmp['status'] = 1;
	$tmp['code'] = 200;
	$data ? $tmp['data'] = $data : false;
	$msg ? $tmp['msg'] = $msg : false;
	if ($exit) {
		json($tmp)->send();
		exit();
	}
	return json($tmp);
}

//失败输出
function error($code=400,$msg='',$exit=true,$throw=false)
{
	$tmp = [];
	$tmp['status'] = 0;
	$tmp['code'] = $code;
	$tmp['msg'] = $msg ? $msg : get_code($code);
	// $detail ? $tmp['detail'] = $detail : false;

	$throw ? json($tmp)->code($code)->send() : json($tmp)->send();
	if ($exit) {
		exit();
	}
}
//获取代码
function get_code($code)
{
	$code_arr = [
		//成功
		200 => 'OK',

		//重定向
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		307 => 'Temporary Redirect',

		//客户端错误
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'NOT FOUND',
		405 => 'Method Not Allowed',
		408 => 'Request Timeout',

		//自定义
		900 => '页面停留时间过长,请刷新页面',
		901 => '请先登录',
		902 => '验证码错误',
		903 => '用户已经存在，请直接登录',
		904 => '用户已经登录',
		905 => '角色已经存在',
		906 => '没有权限进行操作',
		999 => '致命错误'
	];
	if (array_key_exists($code, $code_arr)) {
		return $code_arr[$code];
	}
	return false;
}