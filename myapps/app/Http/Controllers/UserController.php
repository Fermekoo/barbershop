<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Users;
use Validator;
use DB;
use Auth;
class UserController extends Controller
 
{
 
public function authenticate(Request $req){
 
      $validator = Validator::make($req->all(), 
            [
                'password'    => 'required',
                'email'       =>'required|email',

            ]
        );
        if ($validator->fails()) {
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name]  = $values[0];
            }
            $message = 'Login Failed';
            return response()->json(['code' => 401, 'status' => 'Failed', 'message' => $message, 'data' => $data],201);
        }
 
      $user = Users::where('email', $req->input('email'))->first();
      if($user){
                if(password_verify($req->password, $user->password)){
        
                $apiToken = hash('sha256',microtime());
                Users::where('email', $user->email)->update(['api_token' => $apiToken]);
                return response()->json(['code'=>200,'status' => 'Success','api_token' => $apiToken],202);
            }else{
                return response()->json(['code'=>200,'status' => 'Failed', 'message'=>'Password Salah'],401);
            }
      }else{
        return response()->json(['code'=>200,'status' => 'Failed', 'message'=>'Email tidak terdaftar'],401);
      }
 
    
 
   }

   public function register(Request $req){
       $validator = Validator::make($req->all(), 
            [
                 'email' => 'required|email|unique:users',
                 'password' => 'required',
                 'phone'=>'required|regex:/(62)[0-9]{11}/',
                 'password'=>'required|min:8'

            ]
        );
        if ($validator->fails()) {
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name]  = $values[0];
            }
            $message = 'Register Failed';
            return response()->json(['code' => 401, 'status' => 'Failed', 'message' => $message, 'data' => $data],201);
        }
       
        DB::beginTransaction();
        try{
            $add = Users::create([
                'name'=>$req->username,
                'email'=>$req->email,
                'phone'=>$req->phone,
                'password'=>password_hash($req->password,PASSWORD_DEFAULT),
                'is_deleted'=>false
            ]);
        }catch(Exception $e){
            DB::rollBack();
        }
        if($add){
            DB::commit();
            $res['code']=201;
            $res['status']='Success';
            $res['message']='Register berhasil';
        }else{
            $res['code']=401;
            $res['status']='Failed';
            $res['message']='Register Gagal';
        }
        return response()->json($res,201);
   }

   public function profile(){
       $data = Users::find(Auth::user()->id);
       $res['code']=200;
       $res['status']='Success';
       $res['message']='Data profile User';
       $res['data']=$data;
       return response()->json($res);
   }

   public function updateProfile(Request $req){
       $validator = Validator::make($req->all(),[
        
       ]);
   }
 
}    