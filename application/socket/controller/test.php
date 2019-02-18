<?php 
	namespace app\socket\controller;
	use app\api\model\Chat;
	use app\api\model\User;
	use app\api\model\ChatWith;
	use Workerman\Lib\Timer;
	// use think\facade\Session;
	use think\facade\Cookie;

	// 心跳间隔55秒
	define('HEARTBEAT_TIME', 55);
	/**
	 * 
	 */
	class Index extends \think\worker\Server
	{
		protected $socket = 'websocket://127.0.0.1:80/chat';

		/* 消息队列 在心跳的时候进行发送*/
		/* 如果用户存在的话存入数据库后添加到队列 如果用户下线了删除该用户的所有消息 */
		protected $queue = [];
		/*
		$queue = [
			//需要接收的用户
			'to1'=>[
				//发送的用户
				'from1'=>[
					'message1',
					'message2',
				],
				//发送的用户
				'from2'=>[
					'message1',
					'message2',
				]

			],
			//需要接收的用户uid
			'to2'=>[
				//发送的用户uid
				'from1'=>[
					//message为处理后的数据
					//message1 = "{'from':'from1','to':'to2','content':'message','time':'2019-10-21 16:00:00'}"
					'message1',
					'message2',
				],
				//发送的用户
				'from2'=>[
					'message1',
					'message2',
				]

			],
			//全部
			'all'=>[...]
		]
		
		
		*/
		/*
		$queue = [
			'to1'=>[
				//已经处理好的message
				'message1',
				'message2',
				'message3',
			],
			'to2'=>[
				//已经处理好的message
				'message1',
				'message2',
				'message3',
			]
	
		]
		*/


		// onWorkerStart
	    function onWorkerStart($worker) {
	    	// 进程启动后设置一个每秒运行一次的定时器
	    	Timer::add(1, function()use($worker){
				$time_now = time();
				foreach($worker->connections as $connection) {
					# 心跳逻辑start
					// 有可能该connection还没收到过消息，则lastMessageTime设置为当前时间
					if (empty($connection->lastMessageTime)) {
						//添加最后一次活跃时间
						$connection->lastMessageTime = $time_now;
						continue;
					}
					// 上次通讯时间间隔大于心跳间隔，则认为客户端已经下线，关闭连接
					if ($time_now - $connection->lastMessageTime > HEARTBEAT_TIME) {
						//断线
						$connection->close();
						continue;
					}
					#心跳逻辑end



					// #广播逻辑start
					// //放在心跳遍历中的foreach
					// if(is_null($this->queue[$connection->uid])){
					// 	//如果不存在队列中就略过
					// 	continue;
					// }else{
					// 	//存在的话广播该用户
					// 	$data = $this->queue[$connection->uid];
					// 	$connection->send(json($data));
					// 	/*删除消息组*/
					// 	unset($this->queue[$connection->uid]);
					// }
					// #广播逻辑end
					
				}
			});
	    }
	    // onWorkerReload
	    function onWorkerReload($worker) {

	    }
	    // onConnect
	    function onConnect($connection) {
	    	/*判断是否登录*/
	    	// var_dump(Session::get('role_info'));
	    	// $User = new User;
	    	// $token = Cookie::get(COOKIE_LOGIN_STATUS);
	    	// if (is_null($token)) {
	    	// 	$connection->send('Illegal Request t!');
	    	// 	$connection->close();
	    	// 	return;
	    	// }
	    	// /*判断是否登录*/
	    	// $user = $User->field('user.id,role.role')->join('role','role.id = user.role_id')->get(['token'=>$token]);
	    	// if (is_null($user)) {
	    	// 	$connection->send('Illegal Request u!');
	    	// 	$connection->close();
	    	// 	return;
	    	// }
	    	// /*只允许学生跟hr聊天*/
	    	// if (!in_array($user['role'], ['student','hr'])) {
	    	// 	$connection->send('Illegal Request r!');
	    	// 	$connection->close();
	    	// 	return;
	    	// }

	    	// $connection->uid = $user['id'];
	    	
	    	// /*数据库标记在线*/
	    	// $User->where('id',$user['id'])->data(['live'=>1])->update();

	    }
	    // onMessage
	    function onMessage($connection, $data) {

	    	$ChatWith = new ChatWith;
	    	$Chat = new Chat;
	    	$User = new User
	    	# 心跳逻辑start
	    	// 给connection临时设置一个lastMessageTime属性，用来记录上次收到消息的时间
    		// $connection->lastMessageTime = time();
    		# 心跳逻辑end



    		// 其它业务逻辑
    		# 广播逻辑start
    		//$data = {to:uid,content:'123456789'}
   //  		$data = json_decode($data);
   //  		if (is_null($data)) {
   //  			//非法语句 退出
   //  			$connection->send('Illegal Sentence!');
   //  			return;
   //  		}
   //  		$uid = $connection->uid;
   //  		$to = $data['to'];

   //  		$member = $ChatWith->where(['user1'=>$uid,'user2'=>$to])
   //  				->whereOr(['user2'=>$uid,'user1'=>$to])
   //  				->find();

   //  		if (is_null($member)) {
   //  			//非法成员 退出 /* 需要在tp添加*/
   //  			$connection->send('Illegal Member!');
   //  			return;
   //  		}

   //  		$data_pool = [
   //  			'from'=>$uid,
   //  			'to'=>$to,
   //  			'content'=>$data['content'],
   //  			'create_time'=>date('Y-m-d H:i:s'),
   //  			'is_read'=>0
   //  			/*在查看页面的时候tp做出全部设置为1*/
   //  		];

   //  		/*插入数据库*/
   //  		$cid = $Chat->insertGetId($data_pool);
   //  		if ($cid) {
   //  			$connection->send('Illegal 500!');
   //  			return ;
   //  		}

   //  		/*判断是否在线*/
			// $res = $User->get(['live'=>1,'id'=>$to]);
   //  		if (is_null($res)) {
   //  			return;
   //  		}

   //  		/*插入成功后就插入队列*/
   //  		if (is_null($this->queue[$to])) {
   //  			$this->queue[$to] = $data_pool;
   //  		}

    		
    		# 广播逻辑end





	        // $connection->send('hello');
	    }
	    // onClose
	    function onClose($connection) {
	        // $User = new User;

	    	/*在线状态*/
	   //  	if ($connection->uid) {
	   //  		$User->where('id',$user['id'])->data(['live'=>0])->update();
	   //  		/*删除消息组*/
				// unset($this->queue[$connection->uid]);
	   //  	}
	    }
	    // onError
	    function onError($connection, $code, $msg) {
	        // echo "error [ $code ] $msg\n";
	        // $User = new User;
	        /*下线状态*/
	   //  	if ($connection->uid) {
	   //  		$User->where('id',$user['id'])->data(['live'=>0])->update();
	   //  		/*删除消息组*/
				// unset($this->queue[$connection->uid]);
	   //  	}

	    }
	}
