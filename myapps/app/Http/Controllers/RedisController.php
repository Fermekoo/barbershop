<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Users;
class RedisController extends Controller
{
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function redis(){
        $user = Users::all();
        $dx=json_encode($user);
       $a= app('redis')->set('user_data',$dx);
        //return $a;
    }

    public function test_redis(){
       $data=json_decode(app('redis')->get('user_data'));
       echo $data->email.'-'.$data->api_key;

    }

    public function update(){
         $data_user=json_decode(app('redis')->get('user_data'));
        $upd = Users::where('id',$data_user->id)->update(['email'=>'dandifermeko@gmail.com']);
        if($upd){
            $this->redis();
            echo "oke";
        }else{
            echo "gagal";
        }
    }

    public function add(){
        $add = Users::create([
            'name'=>'Gandeng Tangan1',
            'email'=>'kontak@gandengtangan1.org',
            'password'=>'$2y$10$cF4QYo2TG.rVlYixEXJrTeYwglVkRE9OwEK.YO51tmqhT1/B017mK'
        ]);
        if($add){
            $this->redis();
            echo "oke";
        }else{
            echo "gagal";
        }
    }

    public function show_array_redis(){
        $data_user=json_decode(app('redis')->get('user_data'));
        $res['status']=200;
        $res['data']=$data_user;
        return response()->json($res);
    }

    
    
}
