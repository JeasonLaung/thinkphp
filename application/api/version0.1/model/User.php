<?php
namespace app\api\model;

use think\Model;
use app\api\model\Role as RoleModel;

class User extends Model
{
	protected $readonly = ['ceate_time', 'update_time','status','token'];
	protected $hidden = ['status','token'];
	// public function setRoleIdAttr($value='')
	// {
	// 	return (new RoleModel)->get(['name'=>$value])->id;
	// }
	// public function getRoleIdAttr($value='')
	// {
	// 	return (new RoleModel)->get($value)->name;
	// }
}