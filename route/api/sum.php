<?php 
	//测试

	Route::rule('api$','api/index/index')->allowCrossDomain();
	//restful
	//get         查询相关
	//put         全部字段编辑相关
	//patch       单个字段或部分字段编辑相关
	//post        增加相关
	//delete      删除相关

	//status:0   正常
	//status:1   异常

	//增加函数    insert()
	//查询函数    get()
	//大面积查询  select()
	//编辑函数    update()
	//软删除      delete()
	//硬删除      destroy()
	//回收函数    recovery()

	
	//用户       user
	//工作       job
	//公司       company
	//学校       school
	//收藏       like
	//查看       visit

	
	/*
	 * 用户
	 *
	 **/

	//登录
	Route::post('user/login$','api/user/login')
		->validate('\app\api\validate\User','login')
		
		->middleware('app\http\middleware\Logined');
		// ->allowCrossDomain();


	//注册验证码
	Route::post('user/verify$','api/verify/register')
		->validate('\app\api\validate\User','verify')
		->middleware('app\http\middleware\Logined');

	//注册
	Route::post(':role/register$','api/user/register')->validate('\app\api\validate\User','register')
		->pattern(['role' => 'company|student'])
		->middleware('app\http\middleware\Logined');

	//获取role
	Route::get('get_role$','api/user/getRole');

	Route::get('get_user_info$','api/user/getRoleInfo')
		->middleware('app\http\middleware\IsLogin');

	// /*
	//  *
	//  * 工作(hr操作)
	//  *
	//  */

	// //获取或搜索工作岗位
	// Route::get('job/list','api/job/select');

	// //查看单个工作岗位
	// Route::get('job/:id$','api/job/get')->pattern(['id'=>'\d+']);
	// //查看单个工作岗位留下脚印
	// Route::get('visit/job/:id$','api/visit/job')->pattern(['id'=>'\d+']);
	// //查看单个工作岗位留下脚印
	// Route::get('like/job/:id$','api/like/job')->pattern(['id'=>'\d+']);

	// //增加工作岗位
	// Route::post('job/add','api/job/insert')
	// 	->before(['\app\api\behavior\Permission']);

	// //编辑工作岗位
	// Route::put('job/:id$','api/job/update')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除工作岗位
	// Route::delete('job/:id$','api/job/delete')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除工作岗位
	// Route::patch('job/destroy/:id$','api/job/destroy')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除工作岗位
	// Route::patch('job/recovery/:id$','api/job/recovery')
	// 	->before(['\app\api\behavior\Permission']);


	// /*
	//  *
	//  * 公司(hr操作)
	//  *
	//  */

	// //获取或搜索公司
	// Route::get('company/list','api/company/select');

	// //查看单个公司岗位
	// Route::get('company/:id$','api/company/get')
	// 	->pattern(['id'=>'\d+']);
	// //查看单个公司岗位留下脚印
	// Route::get('visit/company/:id$','api/visit/company')
	// 	->pattern(['id'=>'\d+']);
	// //查看单个公司岗位留下脚印
	// Route::get('like/company/:id$','api/like/company')
	// 	->pattern(['id'=>'\d+']);

	// //增加公司
	// Route::post('company/add','api/company/insert')
	// 	->before(['\app\api\behavior\Permission']);

	// //增加公司
	// Route::post('company/add','api/company/insert')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除公司
	// Route::delete('company/:id$','api/company/delete')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除公司
	// Route::patch('company/destroy/:id$','api/company/destroy')
	// 	->before(['\app\api\behavior\Permission']);

	// //软删除公司
	// Route::patch('company/recovery/:id$','api/company/recovery')
	// 	->before(['\app\api\behavior\Permission']);



	/*
	 *
	 * 学生（公司、学校、管理员权限才能查看，学生操作）
	 *
	 */

	// //获取或搜索学生
	// Route::get('student/list','api/student/list')
	// 	->before(['\app\api\behavior\Permission']);

	// //查看单个公司岗位
	// Route::get('student/:id$','api/student/get')
	// 	->pattern(['id'=>'\d+']);
	// //查看单个公司岗位留下脚印
	// Route::get('visit/student/:id$','api/visit/student')
	// 	->pattern(['id'=>'\d+']);
	// //查看单个公司岗位留下脚印
	// Route::get('like/student/:id$','api/like/student')
	// 	->pattern(['id'=>'\d+']);

	//修改学生基本资料
	Route::put('student/base','api/student/base')
		->validate('app\api\validate\Student','base');

	//修改学生期待工作资料
	Route::put('student/expect','api/student/expect')
		->validate('app\api\validate\Student','expect');
	
	//获取学生基本资料
	Route::get('student/info','api/student/info');

	//新增学生标签
	Route::post('student/add_label$','api/student/addLabel')
		->validate('app\api\validate\Student','addLabel');
	//删除学生标签
	Route::post('student/del_label$','api/student/delLabel');

	//设置学生标签
	Route::post('student/labels$','api/student/labels')
	->validate(['labels|标签'=>'array']);

	//显示简历
	Route::patch('student/display','api/student/displayResume');
	//隐藏简历
	Route::delete('student/display','api/student/displayResume');
	
	//新增作品
	Route::post('student/add_work$','api/student/addWork')
		->validate('app\api\validate\Add','student_work');

	//修改作品
	Route::put('student/work/:id$','api/student/updateWork')
		->validate('app\api\validate\Add','student_work')
		->pattern(['id'=>'\d+']);

	//删除作品
	Route::delete('student/work/:id$','api/student/deleteWork')->pattern(['id'=>'\d+']);


#################################################



	//新增教育经历
	Route::post('student/add_education$','api/student/addEducation')
	->validate('app\api\validate\Add','student_education');

	//修改教育经历
	Route::put('student/education/:id$','api/student/updateEducation')
		->validate('app\api\validate\Add','student_education')
		->pattern(['id'=>'\d+']);

	//删除教育经历
	Route::delete('student/education/:id$','api/student/deleteEducation')->pattern(['id'=>'\d+']);


#################################################


	//新增教育经历
	Route::post('student/add_experience$','api/student/addExperience')
	->validate('app\api\validate\Add','student_experience');

	//修改教育经历
	Route::put('student/experience/:id$','api/student/updateExperience')
		->validate('app\api\validate\Add','student_experience')
		->pattern(['id'=>'\d+']);

	//删除教育经历
	Route::delete('student/experience/:id$','api/student/deleteExperience')->pattern(['id'=>'\d+']);


################################################

	//hr
	Route::post('hr/join$','api/hr/joinCompany');

	Route::post('hr/confirm/:id$','api/hr/confirmHr');

	Route::post('hr/ban/:id$','api/hr/banHr');

	Route::post('company/add$','api/hr/createCompany');

	Route::post('company/info$','api/hr/companyInfo');


###############################################
	

	Route::get('company/fix$','api/company/fix');
	Route::get('company$','api/company/index');
	Route::get('company/:id','api/company/get');


	Route::get('job$','api/job/index');
	Route::get('job/fix$','api/job/fix');
	Route::get('job/new$','api/job/new');
	Route::get('job/hot_search$','api/job/hotSearch');
	Route::get('job/hot$','api/job/hot');
	Route::get('job/:id','api/job/get');

	Route::get('college/:id$','api/school/get');
	Route::get('college$','api/school/index');
	//year
	Route::get('college/:id/years$','api/school/year');
	//college_id
	Route::get('college/:id/year/:year/majors$','api/school/major');
	//major_id
	Route::get('college/:college_id/year/:year/major/:major_id/students$','api/school/student');


###########################################
	#学生
	#更新信息
	Route::put('student/info$','api/student/info');
	Route::put('student/description$','api/student/description');

	//学历经验作品资料
	// Route::post('student/education','api/education/insert')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::post('student/experience','api/experience/insert')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::post('student/work','api/work/insert')
	// 	->before(['\app\api\behavior\Permission']);

	// Route::put('student/education','api/education/update')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::put('student/experience','api/experience/update')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::put('student/work','api/work/update')
	// 	->before(['\app\api\behavior\Permission']);

	// Route::delete('student/work','api/work/destroy')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::delete('student/experience','api/experience/destroy')
	// 	->before(['\app\api\behavior\Permission']);
	// Route::delete('student/work','api/work/destroy')
	// 	->before(['\app\api\behavior\Permission']);

	// //查看直接查看简历
	// // Route::get('work/:id','api/work/get')
	// // 	->before(['\app\api\behavior\Permission']);
	// // Route::get('student/:id/work','api/work/select')
	// // 	->before(['\app\api\behavior\Permission']);

	// // Route::get('experience/:id','api/work/get')
	// // 	->before(['\app\api\behavior\Permission']);
	// // Route::get('student/:id/experience','api/work/select')
	// // 	->before(['\app\api\behavior\Permission']);

	// // Route::get('work/:id','api/work/get')
	// // 	->before(['\app\api\behavior\Permission']);
	// // Route::get('student/:id/work','api/work/select')
	// // 	->before(['\app\api\behavior\Permission']);


	Route::post('upload/logo$','api/upload/logo');
	// Route::post('upload/file','api/upload/file');
	Route::post('upload/photo$','api/upload/photo');


