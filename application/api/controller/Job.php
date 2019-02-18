<?php
namespace app\api\controller;
use app\api\model\Job as JobModel;
class Job extends \think\Controller
{
protected function resolve($str=''){
	if (preg_match('/^(.*)-(.*)$/', $str, $arr)) {
		return [(int)$arr[1],(int)$arr[2]];
	}
	if (preg_match('/^(.*)\+$/', $str, $arr)) {
		return [(int)$arr[1]];
	}
	return false;
}
public function index($page=1,$num=10,$city='',$degree='',$experience='',$position=[],$keywords='',$salary='',JobModel $Job)
{

	$Job = $Job->alias('j')
			   ->join('company c','j.company_id = c.id')
			   ->join('city','city.id = j.city','LEFT')
			   ->join('province','province.id = j.province','LEFT')
			   ->field('j.*');

	//不用处理条件
	// $condition = $this->request->only('degree,','get');
	// if (!empty($condition)) {
		// $Job = $Job->where($condition);
	// }

	if (!empty($city)) {
		$Job = $Job->where('city.name','like',$city.'%');
	}

	//需要处理条件
	//$limit
	$page = (int)$page - 1 >= 0 ? (int)$page - 1 : 0;
	$num = (int)$num >0  && (int)$num <= 20 ? (int)$num : 10;
	$limit =  sprintf('%s,%s',$page*$num,$num);

	//岗位
	if (!empty($position)) {
		$Job = $Job->whereIn('title',implode(',', $position));
	}

	//关键字
	if (!empty($keywords)) {
		$keywords = explode(' ', $keywords);
		$Job = $Job->where(function($query) use ($keywords)
		{
			for ($i=0; $i < count($keywords); $i++) {
				$query->where('j.title|j.welfare|province.name|city.name|j.location|c.name','like','%'.$keywords[$i].'%');
			}
		});
	}
	
	//薪酬
	if (!empty($salary)) {
		$arr = $this->resolve($salary);
		if ($arr !== false) {
			if (count($arr)===2) {
				$Job = $Job->where('min_salary',">=",$arr[0])
						   ->where('min_salary',"<=",$arr[1])
						   ->where('max_salary',">=",$arr[0])
						   ->where('max_salary',"<=",$arr[1]);
			}else{
				$Job = $Job->where('min_salary','<=',$arr[0])
						   ->where('max_salary','>=',$arr[0]);
			}
		}
	}

	//学历
	if (!empty($degree)) {
		$arr = $this->resolve($degree);
		if ($arr === false) {
			$Job = $Job->where('degree',(int)$degree);
		}else{
			$Job = $Job->where('min_degree',"<=",$arr[0])
					   ->where('degree','>=',$arr[0]);
		}
	}

	if (!empty($experience)) {
		// halt($experience);
		$arr = explode(',', $experience);
		$arr = array_map(function($v)
		{
			return (int)$v;
		}, $arr);
		$Job = $Job->whereIn('experience',$arr);
	}

	//统计个数用于前端页码
	$count = $Job->count('j.id');
	//每页数据
	$data = $Job->limit($limit)
				->order('update_time desc')

				//工作有效
				->where('j.status','1')
				->where('j.is_display','1')

				//公司有效
				->where('c.status','1')

				//关联公司简要
				->with('company','id')
				->select();
	// return $Job->getLastSql();
	//输出数据
	$data['count'] = $count;

	return success($data);

}

public function new(JobModel $Job)
{
	$data = $Job->alias('j')
		->join('company c','c.id = j.company_id')
		//工作有效
		->where('j.status','1')
		->where('j.is_display','1')

		//公司有效
		->where('c.status','1')

		//关联公司简要
		->with('company')
		->limit(15)
		->order('j.create_time','desc')
		// ->order('j.is_fixed','desc')
		->select();
		return success($data);
}
public function hotSearch(JobModel $Job)
{
	$data = \think\Db::query('SELECT title FROM job GROUP BY title ORDER BY SUM(view_times) DESC LIMIT 5');
	return success($data);
}
public function hot(JobModel $Job)
{
	$data = $Job->alias('j')
		->join('company c','c.id = j.company_id')
		//工作有效
		->where('j.status','1')
		->where('j.is_display','1')

		//公司有效
		->where('c.status','1')

		//关联公司简要
		->with('company')
		->limit(15)
		->order('j.view_times','desc')
		// ->order('j.is_fixed','desc')
		->select();
		return success($data);
}


public function fix(JobModel $Job)
{
	$data = $Job->alias('j')
		->field('j.*')
		->join('company c','c.id = j.company_id')
		//工作有效
		->where('j.status','1')
		->where('j.is_display','1')

		//公司有效
		->where('c.status','1')

		//关联公司简要
		->with('company')
		->limit(15)
		// ->order(['j.create_time'=>'desc','c.is_vip'=>'desc','j.is_fixed'=>'desc'])
		// ->order('j.create_time','desc')
		 
		->order('c.is_vip','desc')
		// ->order('j.is_fixed','desc')
		->select();
		return success($data);
}


public function get($id = '',JobModel $Job)
{
	// $data = $this->request->only('name,create');
	// model('company')->get($this->company_id)->data($data)->save();
	// return $res ? success(false,'success') : error(false);
	return success($Job->get($id));

}
	
// public function createJob()
// {

// 	// $data = $this->request->only('name,create');
// 	// model('company')->get($this->company_id)->data($data)->save();
// 	// return $res ? success(false,'success') : error(false);
// }

// public function updateJob()
// {
// 	$data = $this->request->only('name,create');
// 	model('company')->get($this->company_id)->data($data)->save();
// 	return $res ? success(false,'success') : error(false);
// }

// public function deleteJob()
// {
// 	$data = $this->request->only('name,create');
// 	model('company')->get($this->company_id)->data($data)->save();
// 	return $res ? success(false,'success') : error(false);
// }

// public function destroyJob()
// {
// 	$data = $this->request->only('name,create');
// 	model('company')->get($this->company_id)->data($data)->save();
// 	return $res ? success(false,'success') : error(false);
// }
}