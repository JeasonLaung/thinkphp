<?php 
	header("Content-Type:text/html;charset=utf-8");



	//学校
	$data = file_get_contents('51school.json');
	$data = json_decode($data,true)['data'];

	$dbms='mysql';	   //数据库类型
	$host='localhost'; //数据库主机名
	$dbName='jobweb';  //使用的数据库
	$user='root';	   //数据库连接用户名
	$pass='root';	   //对应的密码
	$dsn="$dbms:host=$host;dbname=$dbName";
	try {
		$pdo = new PDO($dsn, $user, $pass,array(PDO::ATTR_PERSISTENT => true)); //初始化一个PDO对象
		echo "连接成功<br/>";
			//图片用另外一个库,管理员用另一个库
			foreach ($data as $key => $adata) { 
				$sql = "INSERT INTO company(
							company_id,
							company_name,
							company_brief,
							company_big_pic,
							company_created,
							company_logo,
							company_modified,
							company_vip_modified_time,
							company_province,
							company_city,
							company_is_vip,
							company_is_available,
							company_verified,
							company_xu,
							company_license,
							company_credit_code,
							company_has_fix,
							company_expired_time,
							admin_id,
							brief_name
							username,
							)
						VALUES(
								'$adata[id]',
								'$adata[name]',
								'$adata[brief]',
								'$adata[big_pic]',
								'$adata[created]',
								'$adata[logo]',
								'$adata[modified]',
								'$adata[vip_modified_time]',
								'$adata[province]',
								'$adata[city]',
								'$adata[is_vip]',
								'$adata[is_available]',
								'$adata[verified]',
								'$adata[xu]',
								'$adata[license]',
								'$adata[credit_code]',
								'$adata[has_fix]',
								'$adata[expired_time]',
								'$adata[admin_id]',
								'$adata[brief_name]',
								'$adata[username]'

					)";

				// 学校
				// $sql = "INSERT INTO school(
				// 			school_id,
				// 			school_name,
				// 			school_logo,
				// 			school_major,
				// 			school_province,
				// 			school_city,
				// 			school_url,
				// 			user_num)
				// 		VALUES(
				// 				'$adata[school_id]',
				// 				'$adata[school_name]',
				// 				'$adata[school_logo]',
				// 				'$adata[school_major]',
				// 				'$adata[school_province]',
				// 				'$adata[school_city]',
				// 				'$adata[school_url]',
				// 				'$adata[user_num]'
				// 	)";
				echo $pdo->exec($sql);
			}
			$pdo = null;
	} catch (PDOException $e) {
		die ("Error!: " . $e->getMessage() . "<br/>");
	}

	
	
	