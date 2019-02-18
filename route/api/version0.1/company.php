<?php 
	Route::group('api',function()
	{
		Route::get('company/:id$','api/company/get');
		Route::put('company/:id$','api/company/update');
		Route::post('company$','api/company/insert');

	})->allowCrossDomain();

	Route::group('api',function()
	{
		Route::get('hr/:id$','api/hr/get');
		Route::put('hr/:id$','api/hr/update');
		Route::post('hr$','api/hr/insert');

	})->allowCrossDomain();