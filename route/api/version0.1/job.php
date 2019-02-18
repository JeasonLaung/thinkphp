<?php 
	// Route::get('jobs/list','api/job/index')
		 // ->pattern(['position'=>'\w+']);

	Route::group('api',function()
	{
		// 个别展示
		Route::get('job/:id$','api/job/get');
		// 全部展示
		Route::get('job$','api/job/select');

		// 添加
		Route::post('job$','api/job/add')->validate('\app\api\validate\Job');
		// 编辑
		Route::put('job/:id$','api/job/edit');
		// 删除
		Route::delete('job/:id$','api/job/trash');
		// 回收
		Route::post('job/recovery/:id$','api/job/recovery');
		// 彻底删除
		Route::delete('job/destory/:id$','api/job/delete');

		// // 添加
		// Route::post('job/$','api/job/insert')->->validate('\app\api\validate\Job');
		// // 编辑
		// Route::put('job/:id$','api/job/update');
		// // 删除
		// Route::delete('job/:id$','api/job/delete');
		// // 回收
		// Route::post('job/recovery/:id$','api/job/recovery');
		// // 彻底删除
		// Route::post('job/destory/:id$','api/job/destory');

	})->allowCrossDomain();
