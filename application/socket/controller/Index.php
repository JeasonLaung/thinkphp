<?php 
	namespace app\socket\controller;
	use Workerman\Protocols\Http;
	use think\Db;
	use app\api\model\Chat;
	use app\api\model\User;
	use app\api\model\ChatWith;
	use Workerman\Lib\Timer;
	// use think\facade\Session;
	// use think\facade\Cookie;

	// 心跳间隔55秒
	define('HEARTBEAT_TIME', 55);
	/**
	 * 
	 */
	class Index extends \think\worker\Server
	{
		protected $socket = 'websocket://0.0.0.0:2345/';
		/*消息队列*/
		public $queue = [];

		// onWorkerStart
	    function onWorkerStart($worker) {
	    	/*心跳*/
	    	Timer::add(1, function()use($worker){
		        $time_now = time();
		        foreach($worker->connections as $connection) {
		            // 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
		            if (empty($connection->lastMessageTime)) {
		                $connection->lastMessageTime = $time_now;
		                continue;
		            }
		            // 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
		            if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME || empty($connection->uid)) {
		                $connection->close();
		            }
		        }
	    	});

	    }
	    // onWorkerReload
	    function onWorkerReload($worker) {
	    }
	    // onConnect
	    function onConnect($connection) {
	    	/*心跳*/
	    	$connection->lastMessageTime = time();

	    	/*清空id*/
	    	// $connection->id = null;
	    }
	    // onMessage
	    function onMessage($connection, $data) {
	    	// $data = {to:uid,content:'123456789'}
	    	$User = new User;
	    	$Chat = new Chat;
	    	$ChatWith = new ChatWith;

	    	// var_dump($data);
    		$data = json_decode($data,true);
    		if (is_null($data)) {
    			//非法语句 退出
    			$connection->send('Illegal Sentence!');
    			$connection->close();
    			return;
    		}
    		// if (!array_key_exists('type', $data)) {
    		// 	$connection->send('Illegal Type!');
    		// 	return;
    		// }
    		switch (@$data['type']) {
    			case 'login':
    				/*登录用*/
	    			$token = @$data['token'];
	    			if (is_null($token)) {
	    				$connection->send('Illegal Token!');
	    				$connection->close();
	    				break;
	    			}
	    			$user = $User->field('id')->get(['token'=>$token]);
	    			if (is_null($user)) {
	    				$connection->send('Illegal User!');
	    				$connection->close();
	    				break;
	    			}
	    			/*成功登录*/
	    			$connection->send("{\"status\":0,\"msg\":\"登录成功\"}");
	    			$connection->uid = $user['id'];
			    	/*数据库标记在线*/
			    	// $User->where('id',$user['id'])->data(['live'=>1])->update();

    				break;
    			

    			// case 'init':
    			// case 'chat':
    			// case 'history':
    				
    			/*心跳语句*/
    			case 'hello':
    				
    				break;


    			/*初始进入*/
    			case 'init':
    				/*{type:'init',}*/
    				$me = $connection->uid;
    				if (is_null(@$connection->uid)) {
	    				$connection->send('Illegal Requests!');
	    				$connection->close();
	    				break;
    				}
    				// $data = $ChatWith->where('user1|user2',$me)

    				// 	->field('from,to,content,create_time')
    				// 	->group('chat_with_id')
    				// 	->order('create_time desc')
    				// 	->select();

    				$data = Db::query(
    					'SELECT
						 member as id,last_content,update_time
						FROM
						(
						  SELECT
						    user2 AS member,update_time,last_content
						  FROM
						    `chat_with`
						  WHERE
						    `user1` = '.(int)$connection->uid.'

						UNION
						  SELECT
						    user1 AS member,update_time,last_content
						  FROM
						    `chat_with`
						  WHERE
						    `user2` = '.(int)$connection->uid.'
						) as A

						ORDER BY
						  `update_time` DESC');

	    			$data = ['type'=>'init','data'=>$data];
    				$connection->send(json_encode($data));

    				break;

    			/*都判断是否登录*/
    			case 'chat':
    				/*聊天用*/
    				$from = @$connection->uid;
    				$to = @$data['to'];
    				$content = @$data['content'];
    				if (is_null($to) || is_null($content) || is_null($from)) {
	    				$connection->send('Illegal Requests!');
	    				// $connection->close();

    					break;
    				}

    				// var_dump($from);
    				// return ;
    				

    				$res = $ChatWith->where(function($query) use ($from,$to)
			        {
			            $query->where(['user1'=>$from,'user2'=>$to]);
			        })->whereOr(function ($query) use ($from,$to)
			        {
			            $query->where(['user2'=>$from,'user1'=>$to]);
			        })->find();
			        
			        /*检查成员是否存在自己的列表*/
			        if (is_null($res)) {
			        	$connection->send('Illegal Member!');
			        	break;
			        }

			        /*存在*/
			        
			        $data = ['from'=>$from,'to'=>$to,'content'=>$content,'create_time'=>date('Y-m-d H:i:s')];

			        $data['chat_with_id'] = $res['id'];

			       	$res = $Chat->insert($data);
			       	
			       	$res = $ChatWith->where('id',$res['id'])->data(['last_content'=>$content])->update();
					// if (!$res) {
			  //       	$connection->send('Illegal DB!');
			  //      		break;
			  //      	}

			       	/*成功发送*/
	    			$connection->send(json_encode($data));

	    			foreach ($connection->worker->connections as $con) {
	    				if ($con->uid == $to) {
	    					$con->send(json_encode($data));
	    					break;
	    				}
	    			}

    				break;

    			/*聊天历史*/
    			case 'history':
    				/*{type:'history',with:'13'}*/
    				$me = $connection->uid;
    				if (is_null(@$connection->uid)) {
	    				$connection->send('Illegal Requests!');
	    				$connection->close();
	    				break;
    				}
    				$with = @$data['with'];
    				if (is_null($with)) {
    					$connection->send('Illegal With!');
	    				// $connection->close();
	    				break;
    				}

    					

					$data = $Chat->field('from,to,content,create_time')

    				->where(function($query) use ($me,$with)
    				{
    					$query->where(['from'=>$me,'to'=>$with]);
    				})->whereOr(function ($query) use ($me,$with)
    				{
    					$query->where(['to'=>$me,'from'=>$with]);
    				})
    				->select();

    				// $chat_with = $ChatWith->where(function() use ($me,$with)
    				// {
    				// 	$query->where(['user1'=>$me,'user2'=>$with]);
    				// })->whereOr(function () use ($me,$with)
    				// {
    				// 	$query->where(['user2'=>$me,'user1'=>$with]);
    				// })->find();

    				// if (is_null($chat_with)) {
    				// 	$connection->send('Illegal Member!');
	    			// 	break;
    				// }

    				// $Chat->get(['chat_with_id'=>$chat_with['id']]);



	    			$data = ['type'=>'history','data'=>$data];
    				$connection->send(json_encode($data));

    				break;



    			default:
	    			if (is_null(@$connection->uid)) {
    					$connection->send('Illegal Requests!');
	    				// $connection->close();
	    				break;
    				}else{
    					$connection->send('Illegal Type!');
    				}
    				break;
    		}

	    }
	    // onClose
	    function onClose($connection) {
	    	// if ($connection->uid) {
	    		/*数据库标记下线*/
				// $User->where('id',$user['id'])->data(['live'=>0])->update();
				/*删除队列消息*/
				// unset($this->queue[$connection->uid]);
	    	// }
			/*通知下线*/
			// $me = @$connection->uid;
			// if (!is_null($me)) {
			// 	// $res = $ChatWith->field('user1,user2')
			// 	// 	->whereOr('user1|user2',$me)
			// 	// 	->select();
			// 	$res1 = $ChatWith->field('user1 as with')
			// 		->where('user2',$me)
			// 		->select();
			// 	$res2 = $ChatWith->field('user2 as with')
			// 		->where('user1',$me)
			// 		->select();

			// 	$res = [];
			// 	foreach ($variable as $key => $value) {
			// 		# code...
			// 	}

			// 	foreach ($connection->worker->connection as $conn) {
			// 		if () {
						
			// 		}
			// 	}
			// }
			
			
	    	
	    }
	    // onError
	    function onError($connection, $code, $msg) {
	    }
	}
