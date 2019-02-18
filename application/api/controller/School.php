<?php
namespace app\api\controller;
use app\api\model\School as SchoolModel;
use app\api\model\Major;
use app\api\model\Student;
use app\api\model\SchoolYear;

class School
{
	public function index($page='',$num='',$keywords='',SchoolModel $School,Student $Student)
	{
		
		$School = $School->alias('s')
			   ->field('s.id,s.name,s.description,s.logo,count(student.id) as student_num,city.short_name as city,province.short_name as province')
			   ->join('province','province.id=s.province','LEFT')
			   ->join('city','city.id=s.city','LEFT')
			   // ->join('major','major.school_id = s.id','LEFT')
			   ->join('student','student.school_id = s.id','LEFT')
			   ->group('s.id');
		// $Student->join('school','school.id = student.school_id','LEFT')
		// 		->group('student.school_id')
		// 		->field('count(student.id) as student_num');
		// 		select();
		if (!empty($keywords)) {
			$keywords = explode(' ', $keywords);
			$School = $School->where(function($query ) use ($keywords)
			{
				foreach ($keywords as $value) {
					$query = $query->where('province.name|city.name|s.name','like','%'.$value.'%');
				}
				
			});
		}

		$page = abs((int)$page - 1) ? abs((int)$page - 1) : 0;
		$num = abs((int)$num) ? abs((int)$num) : 8;

		$limit = sprintf('%s,%s',$page*$num,$num);

		$data = $School->limit($limit)->order('student_num desc')->select();
		$count = $School->count('s.id');
		$data['count'] = $count;
		return success($data);
	}
	public function get($id='',SchoolModel $School)
	{
		return success($School->alias('s')
							  ->field('province.name as province,city.name as city,s.name,photos,description')
							  ->join('province','province.id=s.province','LEFT')
			   				  ->join('city','city.id=s.city','LEFT')
			   				  ->get($id));
	}
	public function year($id=''/*school_id*/,Student $Student,SchoolModel $School,Major $Major,SchoolYear $Year)
	{
		// return success($Major->where('school_id',$id)->select());
		$res = $Year->field('year')->where('school_id',$id)->select();
		if (is_null($res)) {
			return error(400);
		}
		$tmp = [];
		for ($i=0; $i < count($res); $i++) { 
			array_push($tmp,$res[$i]['year']);
		}
		return success($tmp);
	}
	public function major($id=''/*school_id*/,$year=''/*school_year*/,Student $Student,SchoolModel $School,Major $Major,SchoolYear $Year)
	{
		// return success($Major->where('school_id',$id)->select());
		$school_id = $id;
		$school_year = $year;
		$res = $Year->get(['school_id'=>$school_id,'year'=>$school_year]);
		if (is_null($res)) {
			return error(400);
		}
		$majors = $res['majors'];
		$res = $Major->alias('m')
					->whereIn('m.id',$majors)
					->where('m.school_year',$year)
					->join('student s','s.major_id = m.id','LEFT')
					->group('m.id')
					->field('m.id,m.name,count(s.id) as student_num')
					
					->select();
		return success($res);
	}
	public function student($college_id=''/*school_id*/,$year=''/*school_year*/,$major_id='',Student $Student,SchoolModel $School,Major $Major)
	{
		$search = ['school_id'=>$college_id,'major_id'=>$major_id];
		return success($Student->field('id,logo,name')->all($search));
	}
}