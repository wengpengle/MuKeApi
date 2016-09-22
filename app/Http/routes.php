<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group( ['middleware' => ['web']], function () {

});

#检测用户登录的接口
Route::any('register/index','RegisterController@index');

#检测用户登录的接口
Route::any('login/login','LoginController@checkLogin');

#检测用户注册的接口
Route::any('login/register','LoginController@checkUser');

#加薪利器模块接口
Route::any('raises/index','RaisesController@index');

#网站首页接口
Route::any('index/index','IndexController@index');
