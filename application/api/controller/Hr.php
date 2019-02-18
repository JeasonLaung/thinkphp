<?php
namespace app\api\controller;
use app\api\model\Hr as HrModel;
use think\Controller;
class Hr extends Controller
{
	protected $beforeActionList = [
		'setHrId',
		'commonPermission' => [
			'except'=>[
				'joinCompany',
				'cancelJoinCompany',
			]
		],

		'adminPermission' => [
			'only'=>[
				'confirmHr',
				'banHr',
				'companyInfo',
				'companyVerify',
				'becomeVIP',
				'createCompany'
			]
		]
		// 'isNewHr' => ['only'=>'joinCompany'],
	];
	protected $rid;
	protected $status = false;
	protected $isAdmin = false;
	protected $company_id;

	protected function setHrId(){
		$role_info = session(SESSION_ROLE_INFO);
		if (is_null($role_info)) {
			return error(400,'请先登录');
		}
		if ($role_info['role'] !== 'hr') {
			return error(400,'没有权限');
		}
		$this->status = $role_info['status'];
		$this->rid = $role_info['rid'];
		
		$this->isAdmin = model('hr')->field('is_admin')->get($this->rid)->is_admin;
		// dump(model('hr')->get($this->rid));
	}

######################################################
#
# hr 权限
#
################################################



	protected function commonPermission()
	{
		$Hr = new HrModel;
		if ($this->status == 0) {
			return error(400,'尚未加入公司');
		}
		else if($this->status == -1){
			return error(400,'等待确认');
		}
		$this->company_id = $Hr->get($this->rid)->company_id;
	}

	protected function adminPermission()
	{
		if (!$this->isAdmin) {
			return error(400,'没有权限');
		}
	}
######################################################
#
# hr 管理员
#
######################################################
public function createCompany(HrModel $Hr)
{
	$data = $this->request->only('name,logo,home,location,brief_name,description,city,labels,license_pic,credit_code');
	$company = model('company')->field('id')->get(['name'=>$data['name']]);
	if (is_null($company_id)) {
		return error('已存在公司请直接加入');
	}
	$logo = model('photo')->field('id')->get(['path'=>$data['logo']]);
	$license_pic = model('photo')->field('id')->get(['path'=>$data['license_pic']]);
	if (is_null($logo)) {
		return error(false,'非法图片');
	}

	$data['logo'] = $photo['id'];
	
	$company_id = model('company')->join('company_verify')->insertGetId($data);

	//标注hr，但status还是-1状态,需要网站管理员确认或系统确认
	$Hr->get($this->rid)->data(['company_id'=>$company_id,'is_admin'=>1,'status'=>-1]);
	return success('等待系统确认');
}

public function confirmHr($id,HrModel $Hr)
{
	$hr = $Hr->get(['status'=>-1,'id'=>$id,'company_id'=>$company_id,'is_admin'=>0]);
	if (is_null($hr)) {
		return error(400,'不存在该hr');
	}
	$res = $hr->data(['status'=>1])->save();
	if ($res) {
		return success(false,'确认成功');
	}
	return error(500);
}

//取消进入请求
public function banHr($id,HrModel $Hr)
{
	$Hr->get(['id'=>$id,'company_id'=>$this->company_id]);
	if (is_null($hr)) {
		return error(400,'不存在该hr');
	}
	$res = $hr->data(['status'=>0,'company_id'=>null])->save();
	if ($res) {
		return success(false,'操作成功');
	}
	return error(500);
}


//不能随便改姓名，改公司名称的话需要新增一个公司存储
public function companyInfo()
{
	$data = $this->request->only('logo,home,location,brief_name,description');
	if (array_key_exists('logo', $data)) {
		$logo = model('photo')->field('id')->get(['path'=>$data['logo']]);
		if (is_null($logo)) {
			return error();
		}
		$data['logo'] = $logo['id'];
	}
	$res = model('company')->get($this->company_id)->data($data)->save();
	return $res ? success(false) : error();
}
// public function companyVerify()
// {
// 	$data = $this->request->only('name,credit_code,license_pic');
// 	$company_id = model('company')->fieid(['id'])->get(['name'=>$data['name']]);
// 	if ($company_id) {
// 		return error(false,'公司名已经存在');
// 	}
// 	$data['status'] = 
// 	model('company')->get($company_id)->data($data)->save();
// 	return $res ? success(false,'success') : error(false);
// }
// public function companyVerify()
// {
// 	$data = $this->request->only('name,create');
// 	model('company')->get($this->company_id)->data($data)->save();
// 	return $res ? success(false,'success') : error(false);
// }


##############################################
#	
#   普通Hr
#
##############################################



public function updateHr(HrModel $Hr)
{
	$hr = $Hr->get($this->rid);
	if (is_null($hr)) {
		return error(500);
	}
	$data = $this->request->only('position,name');

	$res = $Hr->get($this->rid)->data($data)->save();
	
	return success($Hr->get($this->rid));
	// if () {
	// 	# code...
	// }
	// $hr->
}
public function updateLogo(HrModel $Hr)
{
	$pid = \Upload::logo(true);
	$res = $Hr->get($this->rid)->data(['logo'=>$pid])->save();
	return success($Hr->get($this->rid));
	// dump($pid);
}






##############################################
#	
#   新Hr
#
##############################################

	public function joinCompany($company_name='',HrModel $Hr)
	{
		if ($this->status == -1) {
			return success(false,'请通知公司管理员确认');
		}else if ($this->status == 1) {
			return error(400,'已绑定公司，不能随意更改');
		}
		$company = model('company')->field('id')->get(['name'=>$company_name]);
		// halt($company_name);

		if (is_null($company)) {
			//需要新增公司
			return error(false,'not company bename this!');
		}

		//加入公司等待公司确认
		$company_id = $company['id'];
		$rid = $this->rid;
		$Hr->get($rid)->data(['status'=>-1,'company_id'=>$company_id])->save();
		session(SESSION_ROLE_INFO.'.status',-1);
		//进入等待确认模式
		return success(false,'等待公司管理员确认');
		
	}

	public function cancelJoinCompany(HrModel $Hr)
	{
		$rid = $this->rid;

		$hr = $Hr->get([
			'status'=>-1,
			'id'=>$this->rid
		]);

		if (is_null($hr)) {
			return error(false,'no company bind before');
		}

		$res = $hr->data([
			'status'=>-2,
			'company_id'=>null
		])->save();

		if ($res) {
			return success(false,'取消绑定成功');
		}
		return error(500);
	}
	

	
	// public function createCompany($value='')
	// {
	// 	# code...
	// }
}