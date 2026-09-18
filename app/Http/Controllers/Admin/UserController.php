<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use Input;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
     /*계정리스트*/
    public function userList() {
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;
        if($userGrade == 9) {
            $users = User::orderBy('id','desc')->paginate(15);
        } else {
            $users = User::where('id','=',$userIdx)->orderBy('id','desc')->paginate(15);
        }
        return view('member.user',compact('users'));
        
    }
    
    /*계정상태변환*/
    public function userState (Request $request, $id) {
        DB::table('users') ->where('id','=',request('id'))
            ->update(['adconfirm' => request('adconfirm_ck')]);
        Alert::success('성공', '상태가 변경되었습니다.');
        return redirect('user');
        
    }
    
    /*계정생성페이지*/
    public function userStore() {
        return view('member.userAdd');
    }
    
    /*계정생성*/
    public function userCreate() {
//        if(request('password') != request('password2')) {
//            
//            return back()->with('message', 'Profile updated!');
//        }
        $chkid =  DB::table('users') -> where('email','=',request('uid'))->value('email');
        if($chkid) {
            Alert::error('에러', '아이디가 중복되었습니다');
            return back();
        }
//        print_r($chkid);
//        exit;
        $user = new User;
        $user -> email = request('uid'); //아이디
        $user -> name = request('username'); //이름
        $user -> username = request('company'); //회사이름
        $user -> password = bcrypt(request('password')); //비밀번호
        $user -> grade = request('grade');; //등급
        $user -> tel = request('tel'); //전화번호
//        $user-> reg_date = date( 'Y-m-d H:i:s', time() );
        
        $user -> save();
        Alert::success('성공', '아이디가 생성되었습니다');
        return redirect('user');
    }
    
     public function userPwd() {
         DB::table('users') ->where('id','=',request('sadver'))
            ->update(['password' => bcrypt(request('password'))]);
//         Alert::success('성공', '비밀번호가 변경되었습니다');
        return view('member.userPwd');
    }
}
