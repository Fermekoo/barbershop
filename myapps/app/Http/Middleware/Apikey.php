<?php

namespace App\Http\Middleware;

use Closure;
use DB;
class Apikey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $key = DB::table('br_apikey')->where(['apikey'=>$request->header('server-key'),'status'=>true, 'env'=>env('APP_ENV')])->select('apikey')->first();

       if(!$key OR $key->apikey != $request->header('server-key')){
           
           return response()->json(['code'=>401, 'status'=>'Failed','message'=>'Server Key Invalid']);

       }else{
           return $next($request);
       }

        
    }
}
