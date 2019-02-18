<?php 
namespace app\api\behavior;

/*
 * 绑定账号行为(因为发送信息是要收钱的所以记录一下，并且如果发现异常用户可以处理)
 *
 */
use think\facade\Session;
use think\Db;
use app\api\model\BindRecord;
use app\api\model\User;
use \Email\Email;
use \IP;

class Bind
{
	protected $compare = [
		'email'=>1,
		'phone'=>2,
		'mobile'=>2,


		//type
		'register'=>1,
		'forget'=>2,
		'reset'=>3,
	];
 
	public function run($params,BindRecord $Record){
		//设置睡眠时间使得不能一直发送同一个账号或者

		$code = random_verify();
		$account = $params['account'];

		$way = $this->compare[$params['way']];
		$type = $this->compare[$params['type']];
		$now = now();
		$time = time();
		// $now = $begin_time = date('Y-m-d H:i:s',$time);
		$dead_time = date('Y-m-d H:i:s',$time+60);
		// $sql = sprintf(,$begin_time,$dead_time,$account);

		// $record = Db::query("SELECT `id` from `bind_record` where `create_time` between '2019-01-15 14:32:26' and '2019-01-15 14:33:26' and `account` = '605251963@qq.com'");
		// $record = Db::query("SELECT `id` from `bind_record` where `create_time` between ? and ? and `account` = ?",[$begin_time,$dead_time,$account]);
		$record = $Record->where('dead_time','>',$now)
						->where('account',$account)
						->field('id')
						->find();
		if ($record) {
			dump($record);
			exit();
		}

		// dump($sql);
		// dump($record);
		// dump(Db::getLastSql());

		// if ($record) {
		// 	return error(400,"请勿频繁操作");
		// }
		// exit();

		$data = [
			'verify_code'=>$code,
			'verify_way'=>$way,
			'verify_type'=>$type,
			'account'=>$account,
			'dead_time'=>$dead_time,
			'ip'=>IP::get(),
		];
		if (null !== $user_info = Session::get(SESSION_USER_INFO)) {
			$data['user_id'] = $user_info['id'];
		}

		$Record->save($data);

		switch ($params['way']) {
			case 'email':

				
				// if () {
				// 	# code...
				// }
				// Email::register($account,$account,$code);
				// echo $type;
				// dump($record);
				break; 
			default:
				echo "other";
				break;
		}


	}
}