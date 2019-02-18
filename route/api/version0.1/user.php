<?php 
	Route::group('api',function()
	{
		// Route::get('index/index','api/index/index')->validate('app\\api\\validate\\User','test');


		// Route::post('verify/email/:type','api/verify/email')->validate('app\\api\\validate\\User','bindEmail')->middleware('app\http\middleware\Logined');
		// Route::post('verify/phone/:type','api/verify/phone')->validate('app\\api\\validate\\User','bindMobile')->middleware('app\http\middleware\Logined');

		//student/reset/by/email
		//company_hr/register/by/email
		//student/reset/by/email
		// Route::post(':  /:type/by/:way','api/user/add')->validate('app\\api\\validate\\User','register');


		//POST 参数 ['way'='email'&]username=605251963@qq.com&password=123456&captcha=captcha
		Route::post(':role/register$','api/user/add')
			//验证注册所需
			->validate('app\\api\\validate\\User','register')
			//是否已经登录
			->middleware('app\http\middleware\Logined');

		//POST 参数 username=c_hr@qq.com&password=123456
		Route::post('user/login$','api/user/find')
			//验证登录所需
			->validate('app\\api\\validate\\User','login')
			//是否已经登录
			->middleware('app\http\middleware\Logined');

	})
	//暂时允许跨域
	->allowCrossDomain();
