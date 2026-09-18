<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    /** 생성 가능한 등급 화이트리스트 (1=광고주, 2=매체, 9=관리자) */
    const ALLOWED_GRADES = [1, 2, 9];

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
        $this->authorizeAdmin();

        $affected = DB::table('users')->where('id', '=', (int) $id)
            ->update(['adconfirm' => (int) $request->input('adconfirm_ck')]);

        if ($affected > 0) {
            Alert::success('성공', '상태가 변경되었습니다.');
        } else {
            Alert::warning('실패', '대상 계정을 찾을 수 없습니다.');
        }
        return redirect('user');
    }

    /*관리자 권한 확인*/
    private function authorizeAdmin() {
        if ((int) Auth::guard('member')->user()->grade < 9) {
            abort(403, '권한이 없습니다.');
        }
    }
    
    /*계정생성페이지*/
    public function userStore() {
        return view('member.userAdd');
    }
    
    /*계정생성*/
    public function userCreate(Request $request) {
        // 계정 생성과 등급 부여는 관리자 권한이다.
        $this->authorizeAdmin();

        $request->validate([
            'uid'      => 'required|email|max:255',
            'username' => 'required|string|max:100',
            'company'  => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
            'grade'    => 'required|integer|in:' . implode(',', self::ALLOWED_GRADES),
            'tel'      => 'nullable|string|max:30',
        ]);

        $chkid = DB::table('users')->where('email', '=', $request->input('uid'))->value('email');
        if ($chkid) {
            Alert::error('에러', '아이디가 중복되었습니다');
            return back();
        }

        $user = new User;
        $user->email    = $request->input('uid');       //아이디
        $user->name     = $request->input('username');  //이름
        $user->username = $request->input('company');   //회사이름
        $user->password = bcrypt($request->input('password')); //비밀번호
        $user->grade    = (int) $request->input('grade'); //등급 (화이트리스트 검증됨)
        $user->tel      = $request->input('tel');       //전화번호

        $user->save();
        Alert::success('성공', '아이디가 생성되었습니다');
        return redirect('user');
    }
    
    /*비밀번호 변경 폼 (조회 전용)*/
    public function userPwdForm() {
        $me = Auth::guard('member')->user();
        // 계정 목록은 관리자에게만 내려준다.
        $accounts = ((int) $me->grade >= 9)
            ? DB::table('users')->orderBy('id', 'desc')->get(['id', 'email'])
            : collect();

        return view('member.userPwd', compact('accounts'));
    }

    /*비밀번호 변경*/
    public function userPwd(Request $request) {
        $me      = Auth::guard('member')->user();
        $isAdmin = (int) $me->grade >= 9;
        $targetId = (int) $request->input('sadver');

        // 본인 변경은 현재 비밀번호 확인, 타인 초기화는 관리자 권한.
        $isAdminReset = $isAdmin && $targetId > 0 && $targetId !== (int) $me->id;

        $rules = ['password' => 'required|string|min:8|confirmed'];
        if (! $isAdminReset) {
            $rules['current_password'] = 'required|string';
        }
        $request->validate($rules);

        if ($isAdminReset) {
            $affected = DB::table('users')->where('id', '=', $targetId)
                ->update(['password' => bcrypt($request->input('password'))]);

            if ($affected === 0) {
                Alert::warning('실패', '대상 계정을 찾을 수 없습니다');
                return back();
            }
            Alert::success('성공', '해당 계정의 비밀번호가 변경되었습니다');
            return redirect('userPwd');
        }

        if (! Hash::check($request->input('current_password'), $me->password)) {
            Alert::error('에러', '현재 비밀번호가 일치하지 않습니다');
            return back();
        }

        DB::table('users')->where('id', '=', $me->id)
            ->update(['password' => bcrypt($request->input('password'))]);

        Alert::success('성공', '비밀번호가 변경되었습니다');
        return redirect('userPwd');
    }
}
