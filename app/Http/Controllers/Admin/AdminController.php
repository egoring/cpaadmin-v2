<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function _construct() {
        $this->middleware('member');
    }
    public function dashboard(){
        return view('member.layout');
    }
    
}
