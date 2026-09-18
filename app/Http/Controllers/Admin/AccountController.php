<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    
    /*계정리스트*/
    public function accountList() {
        
    }
    /*계정상태변환*/
    public function accountState() {
    }
    /*계정생성페이지*/
    public function accountStore() {
        return view('member.eventAdd');
    }
    
    /*계정생성*/
    public function accountCreate() {
    }
}
