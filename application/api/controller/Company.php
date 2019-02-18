<?php
namespace app\api\controller;
use app\api\model\Company as CompanyModel;

class Company extends \think\Controller
{
	public function index($page=1,$num=10,$city='',$keywords=[],CompanyModel $Company)
	{
		$Company = $Company->alias('c')
				->join('job j','c.id = j.company_id','RIGHT')
				->join('province','province.id = c.province')
				->join('city','city.id = c.city');

		// $this->request->only('city','get');
		$num=(int)$num > 0 && (int)$num<20 ? (int)$num : 10;
		$page=(int)$page - 1 >= 0 ? (int)$page-1 : 0;

		$limit=sprintf('%s,%s',$page*$num , $num);
		if (!empty($city)) {
			$Company = $Company->where('city.name','like',$city.'%');
		}
		if (!empty($keywords)) {
			$keywords = explode(" ", $keywords);
			$Company = $Company->where(function($query) use ($keywords)
			{
				for ($i=0; $i < count($keywords); $i++) {
					$query->where('c.name|province.name|city.name|c.location','like','%'.$keywords[$i].'%');
				}
			});
		}
		$data = $Company->limit($limit)
						->field('c.*,count(j.id) as jobs_num')
						->group('j.company_id')
						->order('create_time','desc')
						->select();
		// return $Company->getLastSql();
		$data['count'] = $Company->count();
		return success($data);
	}
	public function fix(CompanyModel $Company)
	{
		return success($Company->alias('c')
							->limit(8)
							->join('job j','c.id = j.company_id','RIGHT')
							->field('c.*,count(j.id) as jobs_num')
							->group('j.company_id')
							->order('is_vip','desc')
							->order('is_fixed','desc')
							->select());
	}

	public function get($id = '',CompanyModel $Company)
	{
		return success($Company->with('jobs')->get($id));
	}

}