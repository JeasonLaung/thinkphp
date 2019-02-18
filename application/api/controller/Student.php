<?php
namespace app\api\controller;
use app\api\model\Student as StudentModel;
use think\facade\Request;
use think\facade\Session;
use think\Controller;

class Student extends Controller
{
	protected $rid;
	protected $beforeActionList = [
		'setStudentId',
	];
	protected function setStudentId()
	{

		$role_info = Session::get(SESSION_ROLE_INFO);
		$user_info = Session::get(SESSION_USER_INFO);
		if (is_null($role_info)) {
			return error(400,'请先登录');
		}
		if ($role_info['role'] !== 'student') {
			return '无权限进行该操作';
		}
		$this->rid = $role_info['rid'];
		// $this->rid = $role_info['id'];
	}


	public function base(StudentModel $Student)
	{
		$data = Request::only('name,degree,birthday,gender,experience');
		$rid = $this->rid;
		$res = $Student->get($rid)->data($data)->save();

		return success($Student->getOneAll($rid));
	}
	public function description($value='',StudentModel $Student)
	{
		$rid = $this->rid;
		$data = $this->request->only('description','put');
		$res = $Student->where('id',$rid)->data($data)->update();
		if ($res) {
			return success(false,'修改成功');
		}else{
			return json(['status'=>0]);
		}
	}
	public function info(StudentModel $Student)
	{
		$rid = $this->rid;
		// halt($rid);
		$data = $this->request->only('name,gender,experience,degree,birthday','put');
		$res = $Student->where('id',$rid)->data($data)->update();
		// $Student->getLastSql();
		if ($res) {
			return success(false,'修改成功');
		}else{
			return json(['status'=>0]);
		}
		// return success($Student->getOneAll($rid));
	}

	public function labels(StudentModel $Student)
	{
		$role_info = Session::get(SESSION_ROLE_INFO);
		$rid = $role_info['rid'];
		$data = Request::only('labels');
		$res = $Student->where($rid)->limit(1)->update();
		return $res ? success() : error(400,'修改标签失败');
	}

	public function addLabel($name='')
	{
		$uid = (Session::get(SESSION_USER_INFO))['uid'];

		$data = ['name'=>$name,'user_id'=>$uid];
		$label = model('label')->get($data);
		// halt($label);
		if(is_null($label)) {
			$label_id = model('label')->insertGetId($data);
		}else{
			$label_id = $label['id'];
		}
		return success($label_id);
	}

	public function delLabel($label_id='')
	{
		$uid = (Session::get(SESSION_USER_INFO))['uid'];

		$data = ['id'=>$label_id,'user_id'=>$uid];
		$label = model('label')->get($data);
		// halt($label);
		if(is_null($label)) {
			return json(['status'=>1]);
		}else{
			$label->delete();
			return success();
		}
	}


	public function expect(StudentModel $Student)
	{
		$data = Request::only('expect_city,expect_salary,expect_job');
		$rid = $this->rid;
		$res = $Student->get($rid)->data($data,true)->save();
		return success($Student->getOneAll($rid));
	}

	public function displayResume(StudentModel $Student)
	{
		if (Request::isPatch()) {
			$status = 1;
		}else if(Request::isDelete()){
			$status = 0;
		}
		$rid = $this->rid;
		$res = $Student->get($rid)->data(['status'=>$status])->save();
		return success($Student->getOneAll($rid));
	}


###############################################c



	//图片上传用upload.photo完成
	public function addWork()
	{
		$rid = $this->rid;
		$data = Request::only('work_name,work_link,photo,from,to,description');
		$data['work_link'] = preg_replace('/^(http(s)?:\/\/)?www\.kujiale\.com\//', 'https://www.kujiale.com/', $data['work_link']);

		$data['create_time'] = now();
		$data['student_id'] = $rid;
		// halt($data);
		$photo = model('photo')->field('id')
							->get(['path'=>$data['photo']]);

		//防止篡改url注入
		if (is_null($photo)) {
			return error(400);
		}
		$data['photo'] = $photo['id'];
		$res = model('student_work')->insertGetId($data);

		return success(false,'添加成功');
	}

	public function updateWork($id)
	{
		$rid = $this->rid;
		
		$work = model('student_work')->get(['id'=>$id,'student_id'=>$rid]);

		if (is_null($work)) {
			return error(400);
		}

		$data = Request::only('work_name,work_link,photo,from,to,description');

		$data['work_link'] = preg_replace('/^(http(s)?:\/\/)?www\.kujiale\.com\//', 'https://www.kujiale.com/', $data['work_link']);

		$photo = model('photo')->field('id')
							->get(['path'=>$data['photo']]);

		//防止篡改url注入
		if (is_null($photo)) {
			return error(400);
		}
		$data['photo'] = $photo['id'];
		$work->data($data)->save();
		return success(false,'修改成功');

	}

	public function deleteWork($id)
	{
		$rid = $this->rid;

		$work = model('student_work')->get(['student_id'=>$rid,'id'=>$id]);
		if (is_null($work)) {
			return error(400);
		}

		$res = $work->delete();
		if ($res) {
			return success(false,'删除成功');
		}
		return error(500);

	}
###############################################c


	public function addEducation()
	{
		$rid = $this->rid;
		$data = Request::only('school_name,degree,major_name,from,to,description');
		$data['create_time'] = now();
		$data['student_id'] = $rid;

		$res = model('student_education')->insertGetId($data);

		return success(false,'添加成功');
	}
	public function deleteEducation($id)
	{
		$rid = $this->rid;

		$education = model('student_education')->get(['student_id'=>$rid,'id'=>$id]);
		if (is_null($education)) {
			return error(400,'不存在教育经历');
		}
		$education->delete();
		return success(false,'删除成功');
	}

	public function updateEducation($id)
	{
		$rid = $this->rid;

		$education = model('student_education')->get(['student_id'=>$rid,'id'=>$id]);
		if (is_null($education)) {
			return error(400,'不存在教育经历');
		}
		Request::only('school_name,degree,major_name,from,to,description');
		$education->data($data)->save();
		return success(false,'修改成功');
	}

###############################################


	public function addExperience()
	{
		$rid = $this->rid;
		$data = Request::only('company_name,position_name,from,to,description');
		$data['create_time'] = now();
		$data['student_id'] = $rid;

		$res = model('student_experience')->insertGetId($data);
		return success(false,'添加成功');
	}
	public function deleteExperience($id)
	{
		$rid = $this->rid;

		$experience = model('student_experience')->get(['student_id'=>$rid,'id'=>$id]);
		if (is_null($experience)) {
			return error(400,'不存在工作经验');
		}
		$experience->delete();
		return success(false,'删除成功');
	}

	public function updateExperience($id)
	{
		$rid = $this->rid;

		$experience = model('student_experience')->get(['student_id'=>$rid,'id'=>$id]);
		if (is_null($experience)) {
			return error(400,'不存在工作经验');
		}
		$data = Request::only('company_name,position_name,from,to,description');
		$experience->data($data)->save();
		return success(false,'修改成功');
	}

}