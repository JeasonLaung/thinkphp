<?php
namespace app\api\controller;
use api\model\LikeCompany as LikeModel;
// use api\model\Company as CompanyModel;
class LikeCompany
{
	public function like($id,LikeModel $Like)
	{
		$stu_id = (Session::get(SESSION_ROLE_INFO))['id'];
		$like = $Like->where('company_id',$id)->find();
		if ($like) {
			return error(200,'已收藏');
		}
		$res = $Like->data(['company_id'=>$id,'student_id'=>$stu_id])->insert();
		if ($res) {
			return success('已收藏');
		}
	}
	public function unlike($id)
	{
		$stu_id = (Session::get(SESSION_ROLE_INFO))['id'];
		$like = $Like->where('company_id',$id)->find();
		if (empty($like)) {
			return error(200,'已取消收藏');
		}
		$res = $Like->get($like['id'])->delete();
		if ($res) {
			return success('已取消收藏');
		}
	}
}