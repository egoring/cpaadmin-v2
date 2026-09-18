<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\loadQuery;
use Maatwebsite\Excel\Concerns\FromView;
use RealRashid\SweetAlert\Facades\Alert;

use App\User;
use App\Exports\userExport;
use App\Exports\memberExport;
use Input;

class MemberController extends Controller
{
    use Exportable;
    public function store(Request $request) {
//       print_r(request('tel2'));
//        exit;
//        if(!request('name')) {
//            return back();
//        }
//           $location = wp_sanitize_redirect(request('name'));
        $name = request('name');
        $age = request('age');

        $etc = request('etc');
        $tel = request('tel1')."-".request('tel2')."-".request('tel3');
        
        $memo = "문의사항 ".request('etc');
        $referer = request('referer');
        if(!request('tel2')||!request('tel3')) {
            return redirect()->away($referer)->with('경고', '전화번호를 입력해 주세요.');
        }
        $detailAgent = $_SERVER['HTTP_USER_AGENT'];
        $midx = request('midx');
        $idx = request('idx');
        $media = request('media');
        $baddr = request('baddr');
        $ccode = request('ccode');
        $cppID = request('cppID');
        $bIp = $_SERVER['REMOTE_ADDR'];
        
        DB::table('member')->insert(
            ['midx' => $midx, 
             'idx' => $idx,
             'inputDate' =>date( 'Y-m-d H:i:s', time() ),
             'bName' => $name,
             'bPhone' => $tel,
             'bIp' => $bIp,
             'media_code' => $media,
             'bAddr' => $baddr,
             'location_url' => $referer,
             'agent' => $detailAgent,
             'ccode' => $ccode,
             'bmemo' => $memo,
             'etc' => $memo
            ]
        );
        
        $lid = DB::getPdo()->lastInsertId();
        
         DB::table('admanager_log')->insert(
            ['midx' => $midx, 
             'idx' => $idx,
             'member_sn' => $lid,
             'referer' => $referer,
             'agent' => $detailAgent,
             'date_time' => DB::raw('NOW()'),
             'cppID' => $cppID
            ]
        );
        return redirect()->away($referer);
    }
    
    public function exceldown(Request $request) {
        $filename = "excel_".date("Ymd")."_".date("His");
        
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;

        if(is_null(request('sadver'))) {
            $sadver = "";
        } else {
            $sadver = request('sadver');
        }
        
        if(is_null(request('search'))) {
            $search = "";
        } else {
            $search = request('search');
        }
        
        if(is_null(request('smode'))) {
            $smode = "";
        } else {
            $smode = request('smode');
        }
        
        if(is_null(request('start_date'))) {
            $start_date = "";
        } else {
            $start_date = request('start_date');
        }
        
        if(is_null(request('end_date'))) {
            $end_date = "";
        } else {
            $end_date = request('end_date');
        }
        return Excel::download(new memberExport($sadver,$search,$smode,$start_date,$end_date),$filename.'.xlsx');
    }
    
    public function csvdown(Request $request) {
        $filename = "csv_".date("Ymd")."_".date("His");
        
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;

        if(is_null(request('sadver'))) {
            $sadver = "";
        } else {
            $sadver = request('sadver');
        }
        
        if(is_null(request('search'))) {
            $search = "";
        } else {
            $search = request('search');
        }
        
        if(is_null(request('smode'))) {
            $smode = "";
        } else {
            $smode = request('smode');
        }
        
        if(is_null(request('start_date'))) {
            $start_date = "";
        } else {
            $start_date = request('start_date');
        }
        
        if(is_null(request('end_date'))) {
            $end_date = "";
        } else {
            $end_date = request('end_date');
        }
        return Excel::download(new memberExport($sadver,$search,$smode,$start_date,$end_date),$filename.'.csv');
    }
    
    
    /*광고주 전체 가져오기*/
    public function get_idx_all() {
    $users = DB::table('users')->get();

    return $users;
    }
    
    
    /*관리자 전체 리스트*/
    public function index()
    {
        
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;
         $member = DB::table('member')
            ->join('users', 'member.idx' ,'=', 'users.id')
            ->where('member.v_status', '=', 0);
        
        if($userGrade == 1) {
            $member = $member -> where('member.idx','=',$userIdx);
        } else if($userGrade == 2) {
            $member = $member -> where('member.midx','=',$userIdx);
        } 
        
        /*관리자 전용*/
        if(request()->has('sadver') ) {
            if($userGrade>8) {
                if(request('sadver')!=0) {
                    $member = $member -> where('member.idx','=',request('sadver'));
                } 
            }
        }
        /*관리자 전용 end*/
        
        
        if(request()->has('smode')) {
            switch(request('smode')) {
            case 1:
            $member = $member -> where('member.media_code','LIKE','%'.request('search').'%');
            break;
            case 2:
            $member = $member -> 
            where('users.username','LIKE','%'.request('search').'%');
            break;
            case 3:
            $member = $member -> where('member.bName','LIKE','%'.request('search').'%');
            break;
            case 4:
            $member = $member -> where('member.bPhone','LIKE','%'.request('search').'%');
            break;
            }
        }
        
//        $date = date("Y-m-d", strtotime( request('end_date') ) );
           $date = date('Y-m-d', strtotime(request('start_date')));
        $date2 = date('Y-m-d', strtotime(request('end_date')));
//date('Y-m-d', strtotime(request('start_date')))
//        date('Y-m-d', strtotime(request('start_date'))
//        $end_time = strtotime("+1 days",$date2 );
//        echo $date2;
        if(request()->has('start_date') && request()->has('end_date')) {
            if(request('start_date') != "" && request('end_date') != "") {
                $member -> where('member.inputdate','>=',request('start_date'));
//            break;
                $member -> where('member.inputdate','<=',request('end_date2'));
//            break;
                
//                $member = $member->whereBetween('member.inputdate', [$date, $date2]);
//                $member = $member->orWhereBetween('member.inputdate', [$date, $date2]);
                
            }
        }
        
        if(request()->has('sort')) {
            $member = $member -> orderBy('member.msn',request('sort'));
        } else {
            $member = $member -> orderBy('member.msn','desc');
        }
        
        if(request()->has('recordPerPage')) {
            $pageNum = request('recordPerPage');
        } else {
            $pageNum = 15;
        }
        
        $member = $member->paginate($pageNum)->appends([
            'smode' => request('smode'), 
            'search' => request('search'), 
            'start_date' => request('start_date'), 
            'end_date' => request('end_date'), 
            'sort' => request('sort'), 
            'sadver' => request('sadver'), 
            'recordPerPage' => request('recordPerPage'), 
        ]);
        
        return view('member.list', compact('member'));
//        return view('member.list', [
//            'invoices' => Invoice::all()
//        ]);

    }
    
//    public function store() {
//        
//    }
    
    /*리스트 삭제*/
    public function delete(Request $request, $id) {
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;
        $cnt = DB::table('member') ->where('msn','=',$id);
        
        switch($userGrade) {
            case 1:
                $cnt = $cnt ->where('idx','=',$userIdx)->count();
                if($cnt > 0) {
                    DB::table('member') ->where('msn','=',$id)
                    ->where('idx','=',$userIdx)
                    ->update(['v_status' => 4]);
                    Alert::success('성공', '삭제되었습니다');
                } else {
                    Alert::warning('실패', '잘못된 접근입니다');
                }
            break;
                
            case 2:
                $cnt = $cnt ->where('midx','=',$userIdx)->count();
                if($cnt > 0) {
                    DB::table('member') ->where('msn','=',$id)
                    ->where('midx','=',$userIdx)
                    ->update(['v_status' => 4]);
                    Alert::success('성공', '삭제되었습니다');
                } else {
                    Alert::warning('실패', '잘못된 접근입니다');
                }
            break;
            
            case 9:
                  $cnt = $cnt -> count();
                if($cnt > 0) {
                    DB::table('member') ->where('msn','=',$id)
                    ->update(['v_status' => 4]);
                    Alert::success('성공', '삭제되었습니다');
                } else {
                    Alert::warning('실패', '잘못된 접근입니다');
                }
            break;
//                
//            default:
//            break
        }
        
//        if($userGrade == 1) { 
//            DB::table('member') ->where('msn','=',$id)
//            ->where('idx','=',$userIdx)
//            ->update(['v_status' => 4]);
//        } else if ($userGrade == 2) {
//            DB::table('member') ->where('msn','=',$id)
//            ->where('midx','=',$userIdx)
//            ->update(['v_status' => 4]);
//        } else {
//            DB::table('member')
//            ->where('msn','=',$id)
//            ->update(['v_status' => 4]);
//        }
//        Alert::success('성공', '삭제되었습니다');
        return back();
    }
   
    public function refresh() {
        echo "<script>location.reload();</script>";
    }
    
    public function accJoin() {
        return view('member.join');
        
    }
    
    public function accList() {
        return view('member.acc');
        
    }
    
    public function eventList() {
        return view('member.event');
    }
    
    public function eventAdd() {
        
    }
    
}
