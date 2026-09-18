<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/list';
    protected $guard = 'member';
//    protected $user_id = 'user_id';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function guard()
    {
        return Auth::guard('member');
    }
    
    public function showLoginForm() {
        echo Auth::guard('member')->check();
        if(Auth::guard('member')->check()) {
//            echo Auth::guard('member')->check();
//            exit;
            return redirect('/list');
        } 
//        else if(Auth::guard('member')->check() === false){
//            Alert::error('에러', '로그인 실패');
//            return view('login');
//        }
        return view('login');
    }
    
    public function logout() {
        Auth::guard('member') ->logout();
        Alert::success('성공', '로그아웃 되었습니다');
        return redirect('/');
        
    }
    
//    public function userId() {
//  return 'user_id';
//}
}
