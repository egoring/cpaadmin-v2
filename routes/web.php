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

//Route::get('/', function () {
//    return view('login');
//});

Route::get('/','Auth\LoginController@showLoginForm');
Route::post('login','Auth\LoginController@login');
    Route::get('/list/exceldown','Admin\MemberController@exceldown');
Route::get('/list/csvdown','Admin\MemberController@csvdown');
//Route::post('/list/exceldown','Admin\MemberController@exceldown');

Route::group(['middleware' => ['member']],function(){
    Route::get('/dashboard','Admin\MemberController@index');
    Route::get('/logout','Auth\LoginController@logout');
    
    Route::get('/list','Admin\MemberController@index');
    Route::get('/list/del/{id}','Admin\MemberController@delete');
    
//    Route::get('/userJoin', 'Admin\MemberController@userJoin');
    Route::get('/user', 'Admin\UserController@userList');
    Route::get('/user/create', 'Admin\UserController@userStore');
    Route::post('/user', 'Admin\UserController@userCreate');
    Route::get('/user/{id}','Admin\UserController@userState');
    Route::get('/userPwd','Admin\UserController@userPwd');
    Route::post('/userPwd','Admin\UserController@userPwd');

    Route::get('/event', 'Admin\EventController@eventList');
    Route::get('/event/create', 'Admin\EventController@eventStore');
    Route::post('/event', 'Admin\EventController@eventCreate');
    
});

Route::post('/crm/member','Admin\MemberController@store');
 

//Route::group(['member' => ['posts']],function(){
//    
//});

//Route::group(['member' => ['posts']],function(){
//    Route::get('/list','Admin\MemberController@index');
//    Route::get('/list/{id}','Admin\MemberController@update');
//});
//
//Route::get('/join', 'Admin\MemberController@accJoin');
//Route::get('/acc', 'Admin\MemberController@accList');
//Route::get('/event', 'Admin\EventController@eventList');
//Route::get('/event/create', 'Admin\EventController@eventStore');
//Route::post('/event', 'Admin\EventController@eventCreate');


//Route::get('/list','Admin\MemberController@index');
////Route::get('/list','Admin\MemberController@get_idx_all');
//Route::get('/list/{id}','Admin\MemberController@update');
//Route::get('/search','Admin\MemberController@index');
//Route::resource('list','Admin\MemberController');



//Route::get('/list', function () {
//    return view('member.list');
//});

//Route::get('/welcome', function () {
//    return view('welcome');
//});
//Route::get('/create',function() {
//    App\User::create([
//       'username'=>'1234',
//        'name'=>'1234',
//        'email'=>'1234',
//        'password'=>bcrypt('1234'),
//        'grade'=>1,
//        'tel'=>'111',
//    ]);
//});
//Route::get('/login',Login function () {
//    return view('login');
//});
//
//Route::get('/dashboard', function () {
//    return view('member.layout');
//});
