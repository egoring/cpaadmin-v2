<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class memberExport implements FromCollection
{
    use Exportable;

//    public function __construct(int $idx)
//    {
//        $this->idx = $idx;
//    }

    
    public function __construct(string $idx, string $search, string $smode, string $start_date, string $end_date)
    {
        $this->idx = $idx;
        $this->search = $search;
        $this->smode = $smode;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        return $this;
    }


    public function collection()
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
        if(!is_null($this->idx) ) {
            if($userGrade>8) {
                if($this->idx!=0) {
                    $member = $member -> where('member.idx','=',$this->idx);
                } 
            }
        }
        
        if(!is_null($this->smode)) {
            switch($this->smode) {
            case 1:
            $member = $member -> where('member.media_code','LIKE','%'.$this->search.'%');
            break;
            case 2:
            $member = $member -> 
            where('users.username','LIKE','%'.$this->search.'%');
            break;
            case 3:
            $member = $member -> where('member.bName','LIKE','%'.$this->search.'%');
            break;
            case 4:
            $member = $member -> where('member.bPhone','LIKE','%'.$this->search.'%');
            break;
            }
        }
        
        if(!is_null($this->start_date) && !is_null($this->end_date)) {
            if($this->start_date!= "" && $this->end_date != "") {
                $member -> where('member.inputdate','>=',request('start_date'));
                $member -> where('member.inputdate','<=',request('end_date2'));
            }
        }
        
        return $member -> get(['member.msn','users.username','member.bName','member.bPhone','member.media_code','member.bIp','member.inputDate','member.location_url','member.etc']);
    }
}



