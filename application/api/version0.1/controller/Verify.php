<?php
namespace app\api\controller;
use think\facade\Session;
use app\api\tool\Email\Email;
use think\facade\Hook;
use app\api\model\BindRecord;
use think\Db;

class Verify
{
    public function email($email='',$type='register',BindRecord $Record)
    {
        \Bind::send(['way'=>'email','type'=>$type,'account'=>$email]);
        // $time = time();
        // $now = $begin_time = date('Y-m-d H:i:s',$time);
        // $dead_time = date('Y-m-d H:i:s',$time+600);
        // $record = $Record->whereBetweenTime('create_time',$begin_time,$dead_time)
        //                 ->where('account',$email)
        //                 ->find();
       // $record =  Db::query("SELECT `id` from `bind_record` where `create_time` < ?  and `account` = ?",[$dead_time,$email]);
        // dump($record);
        // dump(Db::getlastSql());
        // \think\facade\Hook::listen('bind',['way'=>'email','type'=>$type,'account'=>$email]);


    }
    public function mobile($mobile='',$type='register')
    {
        // \think\facade\Hook::listen('bind',['way'=>'mobile','type'=>$type,'account'=>$phone]);
    }
    // public function check($account,$code,$way,$type){
    //     \Bind::check(['account'=>$account,'code'=>$code,'way'=>$way,'type'=>$type]);
    // }
}
