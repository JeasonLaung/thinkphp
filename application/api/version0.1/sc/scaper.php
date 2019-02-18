<?php 
set_time_limit(0);
	require_once 'pdo.php';
	$hdrs = array(
  'http' =>array('header' => 
   "Accept: application/json\r\n" .
   "Accept-Encoding: gzip, deflate\r\n" .
   "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3\r\n" .
   "Accept-Encoding: gzip, deflate\r\n" .
   "Connection: keep-alive\r\n" .
   "User-Agent: Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0\r\n" .
   "X-Requested-With: XMLHttpRequest\r\n" . 
   "Cookie:COMPANY=Wc9I4mG8Vsh8ACpzqrAziGqqR1rfCSF5MtDXMyNaWZGZSIOOm6sujoKLrUps5Qpr9I%252BAHgKssyKQvjR4aFdNPg%253D%253D"
  ),
);
	$context = stream_context_create($hdrs);
	$data = file_get_contents('https://m.v2.51renc.com/upload-files/c_hr_uploads/20181016/04d9c53897312f4a216e09350763f0a4.jpeg',0,$context);
	file_put_contents('1.jpg', $data);

	exit();
	$all = [];
	// $data = file_get_contents('https://m.v2.51renc.com/api/v3/school/get_all_year',0,$context);
	// {"name":"环境艺术设计","id":22,"student_num":60}
	$year_url = 'https://m.v2.51renc.com/api/v3/school/get_all_year?school_id=%s';
	$major_url = 'https://m.v2.51renc.com/api/v3/school/get_all_major?school_id=%s&year=%s';
	$student_url = 'http://enterprise.v2.51renc.com/api/v2/company/resume_search?page=%s&key_word=&city=&degree=&experience=';
	$hr_url = 'https://m.v2.51renc.com/api/v2/company/get_hrs?_company_id=%s';
	// 2016
	$page = 0;
	$m = new M();
	$m->table('company');
	$company_arr = $m->get('',['company_id']);
// http://127.0.0.1/datahandle/scaper.php
	$m->table('hr');
	echo count($company_arr);
	while (true) {
		
		// $data = file_get_contents(sprintf($hr_url,$company_arr[$page]['company_id']),0,$context);
		echo '<b>'.$page.''.'</b>:'.$company_arr[$page]['company_id'].'<br>';
		if (count($company_arr)-1<=$page) {
			break;
		}
		$page++;
		continue;
		echo sprintf($hr_url,$company_arr[$page]['company_id']);
		var_dump($data);
		echo "<br>";
		$data = json_decode($data,true);
		// if(empty($data['data'])){
		// 	continue;
		// }
		$data = $data['data'];
		$all = array_merge($all,$data);
		for ($i=0; $i < count($data); $i++) { 
			$temp = $data[$i];
			$id = $temp['id'];
			$company_id = $temp['_company_id'];
			$created = $temp['created'];
			$modified = $temp['modified'];
			// $labels_arr = $temp['labels'];
			unset($temp['id']);
			unset($temp['_company_id']);
			unset($temp['created']);
			unset($temp['modified']);

			$m->hr_id = $id;
			$m->company_id = $company_id;
			$m->hr_created = $created;
			$m->hr_modified = $modified;


			foreach ($temp as $key => $value) {
				$m->__set($key,$value);
			}
			var_dump($m->add());
		}
		if (count($company_arr)<=$page) {
			break;
		}
		$page++;
		// sleep(1);
	}
	echo "结束";
	file_put_contents('51hr.json', json_encode($all,true));
	// $data = file_get_contents(sprintf($student_url,1),0,$context);
	// $data = json_decode($data,true)['data'];
	// // var_dump( $data);

	// for ($i=0; $i < count($data); $i++) { 
	// 	$temp = $data[$i];
	// 	$id = $temp['id'];
	// 	unset($temp['id']);
	// 	unset($temp['labels']);
	// 	$m->student_id = $id;
	// 	foreach ($temp as $key => $value) {
	// 		$m->__set($key,$value);
	// 	}
	// 	var_dump($m->add());	
	// }

	// $school_all = $m->get('',['school_id']);
	// var_dump($school_all);
	// $m->table('major');
	// foreach ($all as $key => $school) {
	// 	$id = $school['school_id'];
	// 	$url = sprintf($year_url,$id);
	// 	// echo $url;
	// 	// exit();
	// 	$year_data = file_get_contents($url,0,$context);
	// 	$year_data = json_decode($year_data,true);
	// 	if (!empty($year_data['data'])) {
	// 		$year_data = $year_data['data'];
	// 		for ($i=0; $i < count($year_data); $i++) { 
	// 			$url = sprintf($major_url,$id,$year_data[$i]);

	// 			$major_data = file_get_contents($url,0,$context);
	// 			$major_data = json_decode($major_data,true);
	// 			if (!empty($major_data['data'])) {
	// 				$major_arr = $major_data['data'];
	// 				// var_dump($major_arr);
	// 				for ($x=0; $x < count($major_arr); $x++) { 
	// 					// var_dump($major_arr[$x]);
	// 					$one = $major_arr[$x];
	// 					$one['school_id'] = $id;
	// 					$one['year'] = $year_data[$i];
	// 					$major_all[] = $one;
	// 					echo json_encode($major_arr[$x]);
	// 					// $m->major_name = $major_arr[$x]['name'];
	// 					// $m->major_id = $major_arr[$x]['id'];
	// 					// $m->major_year = $x;
	// 					// $m->student_num = $major_arr[$x]['student_num'];
	// 					// $m->add();
	// 					// sleep(0.5);
	// 				}
	// 			}
	// 		}
	// 	}else{
	// 		continue;
	// 	}
	// }
	// echo "结束";
	// file_put_contents('51major.json', json_encode($major_all,true));
	// $data = file_get_contents('51school.json');

	// $data = json_decode($data,true)['data'];
	// // var_dump($data);
	// $school_id_arr = array();
	// foreach ($data as $key => $adata) {
	// 	$school_id_arr[] = $adata['school_id'];
	// }
	// var_dump($school_id_arr);

