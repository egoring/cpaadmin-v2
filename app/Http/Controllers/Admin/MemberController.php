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
use App\Support\RefererGuard;
use App\Support\SearchFilter;

class MemberController extends Controller
{
    use Exportable;
    public function store(Request $request) {
        $name = request('name');
        $etc = request('etc');
        $tel = request('tel1')."-".request('tel2')."-".request('tel3');
        
        $memo = "문의사항 ".request('etc');

        // 허용 호스트의 주소만 되돌려보낸다.
        $referer = RefererGuard::fromConfig()->sanitize(request('referer'));

        if(!request('tel1')||!request('tel2')||!request('tel3')) {
            return $this->backToLanding($referer, '전화번호를 입력해 주세요.');
        }
        if(!request('midx') || !request('idx')) {
            return $this->backToLanding($referer, '잘못된 접근입니다.');
        }
        $detailAgent = $request->userAgent();
        $midx = request('midx');
        $idx = request('idx');
        $media = request('media');
        $baddr = request('baddr');
        $ccode = request('ccode');
        $cppID = request('cppID');
        $bIp = $request->ip();
        
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
        return $this->backToLanding($referer);
    }

    /** 랜딩페이지로 되돌리되, 허용되지 않은 주소면 자체 페이지로 보낸다. */
    private function backToLanding($referer, $message = null) {
        if ($referer === null) {
            return $message === null ? redirect('/') : redirect('/')->with('경고', $message);
        }
        return $message === null
            ? redirect()->away($referer)
            : redirect()->away($referer)->with('경고', $message);
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
        
        // 기간 경계는 서버에서 계산한다 (SearchFilter::endBoundary).
        if (SearchFilter::hasRange(request('start_date'), request('end_date'))) {
            $endBoundary = SearchFilter::endBoundary(request('end_date'));
            if ($endBoundary !== null) {
                $member = $member->where('member.inputdate', '>=', trim(request('start_date')));
                $member = $member->where('member.inputdate', '<', $endBoundary);
            }
        }

        
        $member = $member->orderBy('member.msn', SearchFilter::sortDirection(request('sort')));

        $pageNum = SearchFilter::perPage(request('recordPerPage'));
        
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
//            'invoices' => Invoice::all()
//        ]);

    }

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

            default:
                Alert::warning('실패', '삭제 권한이 없습니다');
            break;
        }
        
//            ->where('idx','=',$userIdx)
//            ->update(['v_status' => 4]);
//            ->where('midx','=',$userIdx)
//            ->update(['v_status' => 4]);
//            ->where('msn','=',$id)
//            ->update(['v_status' => 4]);
        return back();
    }
   
}
