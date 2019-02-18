<?php
namespace app\api\controller;
// use app\api\controller\BaseAction;
use app\api\model\Job as JobModel;
class Job extends \think\Controller
{
	public function select($page='',$num = 20,$keywords='',$position='',$salary='',$degree='',$experience='',$sort='new')//recommond
	{
		$this->request->param('');
	}

	public function get($id='',JobModel $Job)
	{
		$job = $Job->get(['status'=>1,'id'=>$id]);
		if(empty($job)){
			return error(400,'不存在该岗位或已被删除！');
		}
		$with = ['company_info'];

		if ($job['hr_id']) {
			$with[] = 'hr_info';
		}

		$res = $Job->with($with)->get($id);

		return $res ? success($res) : error(400,'该岗位不存或已经被删除！');
	}

	public function company($company_id,JobModel $Job)
	{
		$data = $Job->where('company_id',$company_id)->select();
		return success($data);
	}

	public function add(JobModel $Job)
	{
		$id = $Job->add();
		return $id ? success(['id'=>$id],'添加成功！'):error(400,'添加失败');
	}

	public function edit($id,JobModel $Job)
	{
		$res = $Job->edit();
		return $res ? success($id,'修改成功！'):error(400,'修改失败');
	}

	public function trash($id,JobModel $Job)
	{
		$res = $Job->trash($id);
		return $res ? success(['id'=>$id],'删除成功！'):error(400,'岗位已删除');
	}

	public function recovery($id='',JobModel $Job)
	{
		$res = $Job->recovery($id);
		return $res ? success(['id'=>$id],'还原成功！'):error(400,'岗位已还原');
	}

	public function delete($id,JobModel $Job)
	{
		$res = $Job->get($id)->delete();
		return $res ? success(['id'=>$id],'已经彻底删除！') : error(400,'岗位不存在');
	}
}