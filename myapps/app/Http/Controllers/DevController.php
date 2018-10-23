<?php

namespace App\Http\Controllers;
use Validator;
use Illuminate\Http\Request;
use App\Dev\Apikey;
use DB;
class DevController extends Controller
{
    public function createApikey(Request $req){
         $validator = Validator::make($req->all(), 
            [
                'username'    => 'required',
                'email'       =>'required|email',

            ]
        );
        if ($validator->fails()) {
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name]  = $values[0];
            }
            $message = 'Create Key Failed';
            return response()->json(['code' => 401, 'status' => 'Failed', 'message' => $message, 'data' => $data]);
        }

        $key_development = 'Development-'.base64_encode(hash('sha256',$req->username.'Rahasia Server Development'.date('Y')));
        $key_production = 'Production-'.base64_encode(hash('sha256',$req->username.$req->email.'Rahasia Server Production'.date("Y-m-d")));
        DB::beginTransaction();
        try{
            $create_dev = Apikey::create([
                'apikey'=>$key_development,
                'email'=>$req->email,
                'username'=>$req->username,
                'env'=>'development',
                'status'=>1
            ]);

            $create_prod = Apikey::create([
                'apikey'=>$key_production,
                'email'=>$req->email,
                'username'=>$req->username,
                'env'=>'production',
                'status'=>1
            ]);
        }catch(Exception $e){
            DB::rollBack();
            echo $e->getMessage();
        }
        if($create_dev AND $create_prod){
            DB::commit();
            $res['code']=200;
            $res['status']='Success';
            $res['message']='Server Key berhasil dibuat';
            $res['data']=([
                'server_key_development'=>$key_development,
                'server_key_production'=>$key_production
            ]);
        }else{
            $res['code']=401;
            $res['status']='Failed';
            $res['message']='Server Key gagal dibuat';
        }
        return response()->json($res);
        
    }
}
