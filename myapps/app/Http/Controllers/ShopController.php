<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shop;
use DB;
use Validator;
use Auth;
class ShopController extends Controller
{
    public function add(Request $req){
         $validator = Validator::make($req->all(), 
            [
                 'nama_barber' => 'required',
                 'longitude' => 'required',
                 'latitude'=>'required'
            ]
        );
        if ($validator->fails()) {
            $data = array();
            foreach($validator->messages()->getMessages() as $field_name => $values) {
                $data[$field_name]  = $values[0];
            }
            $message = 'Register Failed';
            return response()->json(['code' => 401, 'status' => 'Failed', 'message' => $message, 'data' => $data],400);
        }
        DB::beginTransaction();
        try{
            $save = Shop::create([
                'nama_barber'=>$req->nama_barber,
                'longitude'=>$req->longitude,
                'latitude'=>$req->latitude,
                'status'=>1
            ]);
        }catch(Exception $e){
            DB::rollBack();
        }
        if($save){
            DB::commit();
            return response()->json(['code'=>200,'status'=>'success','message'=>'data berhasil disimpan'],201);
        }else{
            return response()->json(['code'=>401,'status'=>'failed','message'=>'data gagal disimpan'],400);
        }
        

    }
}
