<?php
namespace app\api\controller;
use think\Db;
class Index
{
    protected $host = 'http://www.51renc.com';
    public function index($id='')
    {
        return \IP::index();
        $page = request()->param('page');
        $limit =($page-1)*100  .','.  ($page)*100; 
        // halt($limit);
        return view('index',['limit'=>$limit]);
        // $pic_arr = model('photo')->field(['path'])->select();

        // $conn = Db::connect([
        //             'type'                  =>  'mysql',        
        //             //  服务器地址               
        //             'hostname'              =>  'localhost',                
        //             //  数据库名            
        //             'database'              =>  'test',                
        //             //  数据库用户名              
        //             'username'              =>  'root',                
        //             //  数据库密码               
        //             'password'              =>  'root'

        // ]);

$data = array(
    'page'=>'', 
    'key_word'=>'', 
    'city'=>'', 
    'degree'=>'',
    'experience'=>''
); 
    
// $data = http_build_query($data); 
 
//$postdata = http_build_query($data);
$options = array(
    'http' => array(
        'method' => 'GET',
        'header' => 
        'Content-type:application/json, text/plain, */*;'.
        'Cookie:COMPANY=Wc9I4mG8Vsh8ACpzqrAziGqqR1rfCSF5MtDXMyNaWZHtCkSU3MDspVA0oNA6xkg7ySRi7P2GK%252FXMvLRDf2ppMw%253D%253D'.
        'User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'
        ,
        // 'content' => $data,
        
        //'timeout' => 60 * 60 // 超时时间（单位:s）
    ),

);
 
$url = "http://m.v2.51renc.com/api/v2/company/resume_search";
$context = stream_context_create($options);

$result = file_get_contents($url, false, $context);
dump($result);
        // dump(file_get_contents('http://enterprise.v2.51renc.com/api/v2/company/resume_search?page=1&key_word=&city=&degree=&experience='));
        // $conn->table('student')->insert([]);
        // return view('index');
        return ;
        set_time_limit(0);
$data = json_decode(file_get_contents(__DIR__.'/../sc/51Company.json'),true)['data']['data'];
dump($data[$id]);
    	// dump(model('bind_record')->get(['status'=>2])->delete());
        // file_put_contents(__DIR__.'/photo/1.jpg',file_get_contents($this->host.'/upload-files/20180718/cce5702a8a3ff41e623894fbc1f276e1.jpg'));
        // dump(hash_file('sha1', __DIR__.'/photo/1.jpg'));
        // unlink(__DIR__.'/photo/1.jpg');
echo model('city')->get(['name'=>'佛山市'])->id;
    	return;
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }
    public function sc(\app\api\model\Job $Job,\app\api\model\City $City,\app\api\model\Province $Province,\app\api\model\Company $Company,\app\api\model\Photo $Photo,\app\api\model\School $School)
    // \app\api\model\City $City,\app\api\model\Province $Province
    {

        $data = json_decode(file_get_contents(__DIR__.'/../sc/51school.json'),true)['data'];
        // dump($data);
        foreach ($data as $one) {
            $p = $Photo->get(['path'=>$one['school_logo']]);
            if ($p) {
                $pid = $p['id'];
            }else{
                $pid = $Photo->insertGetId(['path'=>$one['school_logo'],'create_time'=>now()]);
            }
            
            $adata = [
                'id'=>$one['school_id'],
                'name'=>$one['school_name'],
                'logo'=>$pid,
                'major_type'=>$one['school_major'],
                'province'=>$Province->get(['name'=>$one['school_province']])['id'],
                'city'=>$City->get(['name'=>$one['school_city']])['id'],
                'home'=>$one['school_url'],
                'student_num'=>$one['user_num'],
                'create_time'=>now()
            ];

            echo $School->insert($adata) . '<br>';
        }
        // db('school')->
        return ;
        $data = $Company->where('ISNULL(province)')
                        ->where('city is not null')
                        ->select();
        foreach ($data as $one) {
            $city = $City->get(['name'=>$one['city']]);
            echo $city['province_id'].'<br>';
            if ($city) {
                $Company->get($one['id'])
                ->data(['province'=>$city['province_id']])
                ->save();
            }
        }
        // dump($data);

        return;
        $data = json_decode(file_get_contents(__DIR__.'/../sc/51company.json'),true)['data']['data'];
        // dump($data);
        foreach ($data as $one) {
            $adata = [];
            $city = $City->get(['name'=>$one['city']]);
            $province = $Province->get(['name'=>$one['province']]);
            $adata['city'] = empty($city) ? null : $city['id'];
            $adata['province'] = empty($province) ? null : $province['id'];
            dump($adata);
            $Company->get($one['id'])->data($adata)->save();
        }
        return;
        $data = $Job->alias('j')
            ->join('company c','c.id = j.company_id')
            ->field([
                'j.*',
                'c.name'=>'company_name',
            ])
            ->all([30,50]);
            // echo $Job->getlastSql();
            return json($data);
    return;
        $data = json_decode(file_get_contents(__DIR__.'/../sc/51job.json'),true)['data'];
        foreach ($data as $one) {
            $Job->insert([
                'id'=>$one['id'],
                'company_id'=>$one['company_id'],
                'title'=>$one['title'],
                'experience'=>$one['experience'],
                'degree'=>$one['degree'],
                'location'=>$one['location'],
                'description'=>$one['description'],
                'status'=>$one['status'],
                'create_time'=>$one['created'],
                'update_time'=>$one['modified'],
                'welfare'=>$one['welfare'],
                'province'=>($Province->get(['name'=>$one['province']]))['id'],
                'city'=>($City->get(['name'=>$one['city']]))['id'],
                'max_salary'=>$one['max_salary'],
                'min_salary'=>$one['min_salary'],
                'is_fixed'=>$one['has_fix'],
            ]);
        }

        return ;
        echo "<script>alert(123)</script>";
        echo \Safe::html("<title>456</title><script>alert(123)</script>");
        return ;
        $data = json_decode(file_get_contents(__DIR__.'/../sc/51student.json'),true);

        // model('photo')->
        var_dump(file_get_contents(__DIR__.'/../sc/51student.json'));
        return;
        set_time_limit(0);
// error_reporting(0);
        // $data = json_decode(file_get_contents(__DIR__.'/../sc/51Company.json'),true)['data']['data'];

        // dump($data[0]);
        foreach ($data as $key => $one) {
        // for ($i=76; $i < count($data); $i++) {
            if ($key < 75) {
                continue;
            }
            // $key = $i;
            // $one = $data[$key];
            // dump($one);
            // exit();
            // continue;
            $tmp = [];
            // foreach (['big_pic','logo','small_pic_1','small_pic_2',] as $value) {
            echo $key;
            foreach (['logo'] as $value) {

                // dump($value);
                // exit();
                // continue;
                if ('NULL' !==$one[$value] || $one[$value]) {
                    // try {
                    $file= file_get_contents($this->host.$one[$value]);
                    file_put_contents(__DIR__.'/photo/1.jpg',$file);
                    // } catch (Exception $e) {
                    //     $tmp['logo'] = null;
                    //    continue;
                    // }
                    $hash = hash_file('sha1', __DIR__.'/photo/1.jpg');

                    if($pic = db('photo')->get(['hash'=>$hash])){
                        $tmp[$value] = $pic['id'];
                        continue;
                    }
                   $pic_id = db('photo')->insertGetId([
                        'path'=>$one[$value],
                        'create_time'=>now(),
                        'hash'=>$hash,
                        'host'=>$this->host
                    ]);
                   // $data[$key][$value] = $pic_id;
                   $tmp[$value] = $pic_id;
                    unlink(__DIR__.'/photo/1.jpg');
                    continue;

                }else{
                    continue;
                }
            }
            $data = [
                'is_vip'=>$one['is_vip'],
                'is_auth'=>$one['verified'],
                'is_display'=>$one['is_available'],
                'status'=>1,
                'description'=>$one['brief'],
                'city'=>$one['city'],
                'province'=>$one['province'],
                'create_time'=>$one['created'],
                'id'=>$one['id'],
                'logo'=>$tmp['logo'],
                'update_time'=>$one['modified'],
                'name'=>$one['name'],
                'province'=>$one['province'],
            ];
            if ($company = $Company->get($one['id'])) {
               $company->data($data)->save();
               continue;
            }
             $Company->insert($data);
             continue;
            // dump($data[0]);
            // break;
           // Db::table('company')
           //      ->data([
           //      'is_vip'=>$one['is_vip'],
           //      'is_display'=>$one['is_available'],
           //      'status'=>1,
           //      'brief'=>$one['description'],
           //      'city'=>$one['city'],
           //      'province'=>$one['province'],
           //      'create_time'=>$one['created'],
           //      'id'=>$one['id'],]);
        }










    	// $all = $City->getByAlpha();
    	// return $all;
    	// $city = $City->group(function($query){
    	// 	$query->where('id','>',1);
    	// })->select();
    	// dump($city);
    	// dump($City->getlastSql());
  //   	$return =[];
  //   	foreach ($all as $one) {
		// 	$AtoZ = $one['AtoZ'];
		// 	if (!empty($AtoZ)) {
		// 		if (!array_key_exists($AtoZ, $return)) {
		// 			$return[$AtoZ] = [];
		// 		}
		// 		unset($one['AtoZ']);
		// 		array_push($return[$AtoZ], $one);
		// 	}
		// }
		// return $return;
		// return $return;

    	// $data = json_decode(file_get_contents(__DIR__.'/../sc/51city.json'),true)['data'];

    	// // dump($data);
    	// $sum = [];
    	// foreach ($data as $value) { 
    	// 	$sum = array_merge($value,$sum);
    	// 	// dump($d);
    	// 	// echo $Province->insert($d);
    	// 	// echo '成功插入'.$i.'个城市';
    	// }
    	// // dump($sum);
    	// for ($i=0; $i < count($sum); $i++) { 
    	// 	$d = $sum[$i];
    	// 	$province = explode(',', $d['mergername'])[1];
    	// 	echo $province;
    	// 	$province_id = ($Province->get(['name'=>$province]))['id'];
    	// 	$d['province_id'] = $province_id;
    	// 	echo $province_id;
    	// 	$City->insert($d);
    	// }
    }
}
