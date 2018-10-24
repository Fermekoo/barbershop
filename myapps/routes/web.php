<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
| Global Middleware -> Apikey
| Bootstrap/app.php
|
*/

$router->get('/', function () use ($router) {
    return response()->json(['code'=>200, 'status'=>true, 'message'=>'Welcome to Barbershop APIs'],202);
});



$router->group(['prefix' => 'account'], function ($router) {
        $router->post('login','UserController@authenticate');
        $router->post('create-key','DevController@createApikey');
        $router->post('register','UserController@register');
});

$router->group(['prefix' => 'account', 'middleware'=>['auth']], function($router){
    $router->get('profile','UserController@profile');
});

$router->group(['prefix' => 'shop'], function($router){
    $router->post('add','ShopController@add');
});



