<?php
namespace app\index\controller;
use think\Db;
// set_time_limit(0);
class Index extends \think\Controller
{

    public function index()
    {   
        return view();

















        return ;
        $data = Db::query('
            SELECT
             member
            FROM
            (
              SELECT
                user2 AS member,update_time
              FROM
                `chat_with`
              WHERE
                `user1` = 10

            UNION
              SELECT
                user1 AS member ,update_time
              FROM
                `chat_with`
              WHERE
                `user2` = 10
            ) as A
            ORDER BY
              `update_time` DESC
            ');
        return json($data);
        $arr = [];
        for ($i=0; $i < count($data); $i++) { 
            array_push($arr, $data['member']);
        }



        return ;
        $me = 10;
        $data = Db::table('chat_with')
        ->field('user1 as member')
        ->where('user1',$me)
        // ->join('chat','chat.chat_with_id = chat_with.id')
        ->order('chat_with.update_time desc')
        ->union(function($query) use ($me)
        {
            $query->field('user1 as member')
                ->table('chat_with')
                ->where('user2',$me)
                // ->join('chat','chat.chat_with_id = chat_with.id')
                ->order('chat_with.update_time desc');
        })
        ->select();
        dump($data);
        return Db::getLastSql();
        return ;

        $res = Db::table('chat_with')->where(function($query)
        {
            $query->where(['user1'=>10,'user2'=>10]);
        })->whereOr(function ($query)
        {
            $query->where(['user2'=>10,'user1'=>10]);
        })->find();
        dump($res);
        return Db::getLastSql();

        // return cookie('ss_tk');
        var_dump(Db::table('user')->find(1));
        // halt(function_exists('pcntl_alarm'));
        return 123;
        $i = 0;

        while ($i<9000) {
            $i++;
            $res = $this->make($i);

            if (is_null($res)) {
                continue;
            }

            /*找到学生*/
            $student_id = $res['id'];
            $major_name = $res['base_major'];
            $school_year = (int)$res['gradute_time'];
            $school_id = $res['_school_id'];

            if ($major_name == 'None' || $school_year < 2000 || !$school_id) {
                Db::table('student')
                  ->where('id',$i)
                  ->data(['major_id'=>''])
                  ->update();
                continue;
            }

            $res= Db::table('major')
            ->get(['school_year'=>$school_year,'school_id'=>$school_id,'name'=>$major_name]);
            if (is_null($res)) {
                continue;
            }

            Db::table('student')->where('id',$student_id)->data(['major_id'=>$res['id']])->update();
            // break;
            // ->update();
        }
        return ;
        $i = 0;
        while ($i<9000) {
            $i++;
            $res = $this->make($i);
            if (is_null($res)) {
                continue;
            }

            $school_year = (int)$res['gradute_time'] - 3;

            $school_year = $school_year > 0 ? $school_year : 0;
            // return $school_year;
            Db::table('student')
            ->where(['id'=>$i])
            ->data(['school_year'=>$school_year])
            ->update();
            // dump(Db::getLastSql());
            // return ;

        }
        

        return;
        $db = Db::connect('mysql://root:root@localhost:3306/test#utf8');

        $i = 0;
        while ($i<9000) {
            $i++;
            $res = $db->table('student')
                      ->get($i);
            if (is_null($res)) {
                continue;
            }
            $school_year = (int)$res['gradute_time'] - 4;

            $school_id = (int)$res['_school_id'];

            $major  = $res['base_major'];
            if ($major == 'None') {
                continue;
            }
            $m_res = $db->table('major')->get(['name'=>$major]);
            halt($m_res);
            // if (is_null($m_res)) {
            //     $db->table('major')->insert([''=>]);
            // }
            dump($m_res);
            // $m_res



            // $res['school_year'];
        }
        

        return 123;
        // return json(Db::connect('mysql://root:root@localhost:3306/test#utf8')
          // ->table('school')->get(120));

        $i = 3171;
        while($i<9000){
            $i++;
            $res = Db::table('student')
                     ->get(['id'=>$i]);
            if (is_null($res)) {
                continue;
            }
            if ($res['labels'] == '[]') {
                Db::table('student')->where('id',$i)->data(['labels'=>''])->update();
                continue;
            }
            if ($res['labels']) {
                // return $res['labels'];
                $labels = preg_replace_callback("/\"([^\"]+)\",/", function ($matches)
                {
                    $matches[1] = preg_replace("/\s/",'',$matches[1]);
                    return "\"".preg_replace("/(u[^u]{4})/",'\\\\\1',$matches[1]).'",';
                }, $res['labels']);
                $labels = json_decode($labels,true);
                $data = [];
                
                if (strtolower(gettype($labels))!='array') {
                    dump($labels);
                    echo "<br>";
                   continue;
                }
                for($a = 0; $a<count($labels);$a++) {
                    // halt($labels[$a]['label_name']);
                    $label_name = $labels[$a]['label_name'];
                    $res = Db::table('label')->get(['name'=>$label_name]);
                    // halt($res);
                    if (is_null($res)) {
                        // halt($labels);
                        $label_id = Db::table('label')->insertGetId(['name'=>$label_name,'user_id'=>$labels[$a]['_users_id']]);

                    }else{
                        $label_id = $res['id'];
                    }

                    array_push($data, $label_id);

                }

                $labels = implode(',', $data);
                // halt($labels);
                Db::table('student')->where('id',$i)->data(['labels'=>$labels])->update();
                # preg_replace("/\"[^\"]+\"/", "123", $res['labels']);
            }
            // return $res;

        }


        return;

        $i = 0;
        while($i<9000){
            $i++;
            $arr = [];
            $res = $this->test($i);
            if (is_null($res)) {
                continue;
            }
            $email = (60000000 + $i*2).'@qq.com';
            if (!is_null(Db::table('user')->get(['username'=>$email]))) {
                
                $uid = (Db::table('user')->get(['username'=>$email]))['id'];
            }
            else{
                //生成用户
                $uid = Db::table('user')->insertGetId([
                   'username'=> $email,
                   'password'=>'8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92',
                   'create_time'=>date('Y-m-d H:i:s'),
                   'role_id'=>'1'
                ]);
            }
            // return $res['id'];
            // return json(Db::table('student')->get(['id'=>$res['id']]));
            if (!is_null(Db::table('student')->get(['id'=>$res['id']]))) {
                dump($res);

                continue;
                // return '1;';
            }
            // return json($res);
            $compare = [
                'id'=>'id',
                'base_name'=>'name',
                'base_major'=>'major_id',
                'base_degree'=>'degree',
                'base_gender'=>'gender',
                'base_experience'=>'experience',
                'base_logo'=>'logo',
                'modified'=>'update_time',
                '_school_id'=>'school_id',
                'base_birth'=>'birthday',
                'has_gradute'=>'has_gradute',
                'has_ku_work'=>'has_ku_work',
                'last_login_time'=>'last_login_time',
                'labels'=>'labels',
                'expect_city'=>'expect_city',
                'expect_salary'=>'expect_salary',
                'expect_career'=>'expect_job',


            ];
            if ($res['base_birth_month'] != 'None' && $res['base_birth_year'] != 'None') {
                $res['base_birth'] = $res['base_birth_year'] . '-' . $res['base_birth_month'] . '-01';
            }else{
                $res['base_birth'] = null;
            }
            //重置$data
            $data = ['user_id'=>$uid];
            foreach ($res as $key => $value) {
                if ($res[$key]=='None') {
                   $res[$key] = '';
                }
                if ($key == 'base_major' && $res['base_major']!= 'None') {
                    $major_id = $this->major($res['base_major'],$res['_school_id']);
                    $data['major_id'] = $major_id;
                    continue;
                }
                if ($key == 'base_gender' && $res['base_gender']!= 'None') {
                    if ($res['base_gender']=='male') {
                        $data['gender'] = 1;
                    }else if($res['base_gender']=='female'){
                        $data['gender'] = 2;
                    }
                    else{
                        $data['gender'] = 0;
                    }
                     continue;
                }
                if ($key == 'expect_city' && $res['expect_city']!= 'None') {
                    if ($res['expect_city'] == '') {
                        $data['expect_city'] = null;
                    }else{
                        $data['expect_city'] = (Db::table('city')
                          ->get(['short_name'=>$res['expect_city']]))['id'];
                    }
                    continue;
                }
                if ($key == 'base_logo' && $res['base_logo']!= 'None') {
                    $pres = Db::table('photo')
                      ->get(['path'=>$res['base_logo']]);
                    if (is_null($pres)) {
                        $pid = Db::table('photo')->insertGetId(['path'=>$res['base_logo']]);
                    }
                    else{
                        $pid = $pres['id'];
                    }
                    $data['logo'] = $pid;
                    continue;
                }

                $degree_arr = ['','高中','大专','本科','研究生'];
                if ($key == 'base_degree' && $res['base_degree']!= 'None') {
                    $data['degree'] = array_search($res['base_degree'], $degree_arr);
                }

                if (array_key_exists($key, $compare)) {
                    //我的字段名
                    
                    $field = $compare[$key];
                    $data[$field] = $res[$key];

                }
            }
            //重置数据字段完毕
            Db::table('student')
              ->insertGetId($data);

        }
        return;
        // $i = 0;
        // while($i<9000){
        //     $i++;
        //     $arr = [];
        //     echo $i.'<br>';
        //     $data = $this->test($i);
        //     if (is_null($data)) {
        //         echo "这个没有";
        //         continue;
        //     }
            // !empty($data->left_img) ? array_push($arr, $data['left_img']) : false;
            // !empty($data->right1_img) ? array_push($arr, $data['right1_img']) : false;
            // !empty($data->right2_img) ? array_push($arr, $data['right2_img']) : false;
            // if ($data['left_img']) {
            //     array_push($arr, $this->pic($data['left_img']));
            //     // return json($arr);
            // }
            // if ($data['right1_img']) {
            //     array_push($arr, $this->pic($data['right1_img']));
            //     // return json($arr);
            // }
            // if ($data['right2_img']) {
            //     array_push($arr, $this->pic($data['right2_img']));
            //     // return json($arr);
            // }
            // if (!$data['description']) {
            //     continue;
            //     // return json($arr);
            // }
            // dump($arr);

            // dump(json_encode($arr));
            // return;
            // return;
            // $str = json_encode($arr);
            // $str = substr($str, 1,-1);
            // Db::connect('mysql://root:root@localhost:3306/fuck_life#utf8')
            //   ->table('school')
            //   ->where('id',$i)
            //   ->data(['photos'=>$str])
            //   ->update();
            // Db::connect('mysql://root:root@localhost:3306/fuck_life#utf8')
            //   ->table('school')
            //   ->where('id',$i)
            //   ->data(['description'=>$data['description']])
            //   ->update();
            
        // }
    	// return phpinfo();
        // behavior('bind');
        
        // return;
    	// return \Navigator::browser();
    	// dump($a);
    	 // \IP::check();
    	// return ;
    	// return \IP::get();
        // $data = '{"msg":"success","status":0,"data":[{"id":28,"label_name":"底薪+提成+奖金","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:02:00","parent_name":null},{"id":29,"label_name":"年底双薪","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:07:13","parent_name":null},{"id":30,"label_name":" 带薪年假 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:09","parent_name":null},{"id":31,"label_name":"扁平管理 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:15","parent_name":null},{"id":32,"label_name":"年度旅游 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:21","parent_name":null},{"id":33,"label_name":"公司氛围好 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:28","parent_name":null},{"id":34,"label_name":"一对一辅导 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:34","parent_name":null},{"id":35,"label_name":"下午茶 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:40","parent_name":null},{"id":36,"label_name":"食宿补贴 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:46","parent_name":null},{"id":37,"label_name":"90后团队 ","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:52","parent_name":null},{"id":38,"label_name":"发展空间大","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:08:57","parent_name":null},{"id":39,"label_name":" 设计大咖多","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:09:03","parent_name":null},{"id":40,"label_name":" 提成高","label_type":2,"level":2,"parent_id":0,"is_available":1,"created":"2018-08-20 17:09:07","parent_name":null}]}';

        // $i = 0;
        // while ($i < 150) {
        //     $res = Db::table('label')->get($i);
        //     if (null===$res) { 
        //         continue;
        //     }
        //     // foreach ($res as $key => $value) {
        //     //     $res[$key] = preg_replace(' ', '', $res[$key]);
        //     // }
        //     $res->name = preg_replace(' ','',$res->name);
        //     $res->save();
        //     $i++;
        // }
        

        // $d = json_decode($data,true)['data'];
        // $data = [];
        // foreach ($d as $value) {
        //     $data['id'] = $value['id'];
        //     $data['name'] = $value['label_name'];
        //     $data['create_time'] = $value['created'];
        //     $data['type'] = $value['label_type'];
        //     $data['level'] = $value['level'];
        //     $data['property'] = $value['parent_id'];
        //     $res = Db::table('label')->get($data['id']);
        //     if (null === $res) {
        //         Db::table('label')->insert($data);
        //     }
        //     // Db::table('label')->insert($data);

        //     $data2['id']=$value['parent_id'];
        //     $data2['name']=$value['parent_name'];

        //     $res = Db::table('label_property')->get($data2['id']);
        //     if (null === $res) {
        //         Db::table('label_property')->insert($data2);
        //     }

        // }
        // model('label')->
        // return 'ok';
        // return json($this->request->header());
        
        // for
        // $labels = array_rand(range(128, 152),$num);
        // $a = 0;
        // $arr = array_merge(range(28, 40),range(75, 110));
        // while ($a<1000) {
        //     echo $a;
        //     $labels = [];
        //     $num = rand(0,3);
        //     if ($num==0) {
        //         continue;
        //     }elseif ($num==1) {
        //         $rand = [array_rand($arr,1)];
        //     }else{
        //         $rand = array_rand($arr,$num);
        //     }
        //     for ($i=0; $i < $num; $i++) {
        //         // dump($rand);
        //         // for ($i=0; $i < $num; $i++) { 
        //         $r = $arr[$rand[$i]];
        //         array_push($labels, $r);
        //         // }
                
        //     }
        //     // halt($labels);
        //     // $rand = mt_rand(0,10000);
        //     // halt('end');
        //     $data = ['labels'=>json_encode($labels)];
        //     // $data = ['view_times'=>$rand];
        //     // Db::table('job')->where('id',$a)->data($data)->update();
        //     Db::table('company')->where('id',$a)->data($data)->update();

        //     $a++;
        // }


        // return ;

        // return ;
    	// return view('test');
        return '<style type="text/css">*{ padding: 0; margin: 0; } div{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px;} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:) </h1><p> ThinkPHP V5.1<br/><span style="font-size:30px">12载初心不改（2006-2018） - 你值得信赖的PHP框架</span></p></div><script type="text/javascript" src="https://tajs.qq.com/stats?sId=64890268" charset="UTF-8"></script><script type="text/javascript" src="https://e.topthink.com/Public/static/client.js"></script><think id="eab4b9f840753f8e7"></think>';
    }
    protected function test($id)
    {
        return Db::connect('mysql://root:root@localhost:3306/test#utf8')
          ->table('student')->get($id);
    }
    protected function pic($path)
    {
        $res = Db::table('photo')->get(['path'=>$path]);
        if (is_null($res)) {
            return (int)(Db::table('photo')->insertGetId(['path'=>$path]));
        }else{
            return (int)$res['id'];
        }
    }
    public function major($major='',$school_id='')
    {
        return (Db::table('major')
                  ->get(['name'=>$major,'school_id'=>$school_id]))['id'];
    }
    public function school($value='')
    {
        return 456;
    }
    public function make($id='')
    {
        return Db::connect('mysql://root:root@localhost:3306/test#utf8')
            ->table('student')
            ->get(['id'=>$id]);
    }
    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
