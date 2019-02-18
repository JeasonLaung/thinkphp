<?php 
	$data = file_get_contents('51label2.json');
	$data = json_decode($data,true)['data'];
	// var_dump($data);
	// $arr = [
	// 	'job_id'=>'id',
	// 	'job_title'=>'title',
	// 	'job_phone'=>'phone',
	// 	'job_experience'=>'experience',
	// 	'job_degree'=>'degree',
	// 	'job_location'=>'location',
	// 	'job_description'=>'description',
	// 	'job_status'=>'status',
	// 	'job_created'=>'created',
	// 	'job_modified'=>'modified',
	// 	'job_welfare'=>'welfare',
	// 	'job_province'=>'province',
	// 	'job_city'=>'city',
	// 	'job_max_salary'=>'max_salary',
	// 	'job_min_salary'=>'min_salary',
	// 	'job_sort_time'=>'job_sort_time',
	// 	'job_has_fix'=>'has_fix',
	// 	'job_expired_time'=>'expired_time',
	// 	'company_id'=>'company_id',

	// ];
	$arr = [
		'label_id'=>'id',
		'label_name'=>'label_name',
		'label_type'=>'label_type',
		'label_level'=>'level',
		'parent_id'=>'parent_id',
		'label_is_available'=>'is_available',
		'label_created'=>'created',
		'parent_name'=>'parent_name'
	];
	require_once 'pdo.php';
	$m = new M();
	$m->table('label');

	for ($i=0; $i < count($data); $i++) { 
		foreach ($arr as $key => $value) {
			$m->__set($key,$data[$i][$value]);
		}
		var_dump($m->add());
	}

	// for ($i=0; $i < count($data); $i++) { 
	// 	$m->where(['job_id'=>$data[$i]['id']]);
	// 	foreach ($arr as $key => $value) {
	// 		$m->__set($key,$data[$i][$value]);
	// 		// echo $m->sql;
	// 	}
	// 	$m->update();
	// }
	
	
	// $m->__set('a',2);
	// for ($i=0; $i < count($data); $i++) { 
	// 	$m->where(['company_id'=>$data[$i]['id']]);
	// 	$m->company_brief = $data[$i]['brief'];
	// 	$m->update();
	// }
	// var_dump($m);
	
	// $arr = [
	// 	'company_id'=>'id',
	// 	'company_logo'=>'logo',
	// 	'company_modified'=>'modified',
	// 	'company_name'=>'name',
	// 	'company_province'=>'province',
	// 	'company_city'=>'city',
	// 	'company_vip_modified_time'=>'vip_modified_time',
	// 	'company_big_pic'=>'big_pic',
	// 	'company_is_vip'=>'is_vip',
	// 	'company_is_available'=>'is_available',
	// 	'company_xu'=>'xu',
	// 	'company_license'=>'license',
	// 	'company_credit_code'=>'credit_code',
	// 	'company_has_fix'=>'has_fix',
	// 	'company_expired_time'=>'expired_time',
	// 	'company_brief_name'=>'brief_name',
	// 	'company_small_pic_1'=>'small_pic_1',
	// 	'company_small_pic_2'=>'small_pic_2',
	// 	'user_username'=>'username',
	// 	'admin_id'=>'admin_id'
	// ];
	// $m->id = 56;
	// $m->name = '我是';
	// var_dump($m->get('0,5'));
	// new M();
	// $pdo = new PDO('mysql:host=localhost;dbname=test','root','root');
	// $sql = "INSERT INTO a(b) VALUES('我是')";
	// $pdo->exec($sql);

	// var_dump($m);
	// var_dump($m->query("INSERT INTO a(b) VALUES('我是')"));
	// echo "<br>";
	// var_dump($m);

	// $m->name = '\'我是\'';
	// $m->get();
	// echo "<pre>";
	// var_dump($m->get());
	// echo "</pre>";

	// $m->table('major');
	// $m->major_id = 'avc';
	// $m->add();
	// var_dump($m);
	// for ($i=0; $i < count($data); $i++) { 

	// 	// echo $data[$i]['id'].$data[$i]['year'].$data[$i]['name'].$data[$i]['school_id'].$data[$i]['student_num'].'<br>';
		
	// 	// $m->major_id = $data[$i]['id'];
	// 	// $m->major_year = $data[$i]['year'];
	// 	// $m->major_name = '\''.$data[$i]['name'].'\'';
	// 	// $m->school_id = $data[$i]['school_id'];
	// 	// $m->student_num = $data[$i]['student_num'];
	// 	// $m->add();
	// 	foreach ($arr as $key => $value) {
	// 		$m->__set($key,$data[$i][$value]);
	// 		// echo $data[$i][$value];
	// 		// echo "<pre>";
	// 		// var_dump($m);
	// 		// echo "</pre>";

			
	// 	}
	// 	echo "<pre>";
	// 	var_dump($m->add());
	// 	echo "</pre>";
		
		
	// }
	