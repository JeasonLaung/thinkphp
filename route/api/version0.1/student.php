<?php 
	Route::group('api',function()
	{
		Route::get('student/:id$','api/student/get');
		Route::put('student/:id$','api/student/update');
		Route::post('student$','api/student/insert');

	})->allowCrossDomain();