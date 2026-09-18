<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/','Auth\LoginController@showLoginForm');
Route::post('login','Auth\LoginController@login');

Route::group(['middleware' => ['member']],function(){
    // 다운로드는 반드시 인증 뒤에 있어야 한다 (리드 전체가 나가는 경로)
    Route::get('/list/exceldown','Admin\MemberController@exceldown');
    Route::get('/list/csvdown','Admin\MemberController@csvdown');

    Route::get('/dashboard','Admin\MemberController@index');
    Route::get('/logout','Auth\LoginController@logout');
    
    Route::get('/list','Admin\MemberController@index');
    Route::get('/list/del/{id}','Admin\MemberController@delete');
    
    Route::get('/user', 'Admin\UserController@userList');
    Route::get('/user/create', 'Admin\UserController@userStore');
    Route::post('/user', 'Admin\UserController@userCreate');
    Route::post('/user/{id}/state','Admin\UserController@userState');
    Route::get('/userPwd','Admin\UserController@userPwdForm');
    Route::post('/userPwd','Admin\UserController@userPwd');

    Route::get('/event', 'Admin\EventController@eventList');
    Route::get('/event/create', 'Admin\EventController@eventStore');
    Route::post('/event', 'Admin\EventController@eventCreate');
    
});

Route::post('/crm/member','Admin\MemberController@store');
