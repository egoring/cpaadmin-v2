<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Event;
use Input;
use RealRashid\SweetAlert\Facades\Alert;
class EventController extends Controller
{
    /*이벤트 리스트*/
    public function eventList() {
        $userGrade = Auth::guard('member')->user()->grade;
        $userIdx = Auth::guard('member')->user()->id;
        if($userGrade == 9) {
            $event = DB::table('event') ->
            select(DB::raw('*, (SELECT username FROM users WHERE event.advertiser_id = users.id ) as ac_co_name , (SELECT username FROM users WHERE event.media_id = users.id ) as me_co_name'));
            
        } else {
            $event = DB::table('event') ->
            select(DB::raw('*, (SELECT username FROM users WHERE event.advertiser_id = users.id ) as ac_co_name , (SELECT username FROM users WHERE event.media_id = users.id ) as me_co_name')) -> where ("event.advertiser_id","=",$userIdx);
            
        }
        
           $event = $event ->orderBy('event.esn','desc')->paginate(15);
        return view('member.event', compact('event'));
    }
    
    /*이벤트 생성 페이지*/
    public function eventStore() {
        return view('member.eventAdd');
    }
    
    /*이벤트 생성*/
     public function eventCreate() {
         $model = new Event;
         $model-> advertiser_id = request('adv_id');
         $model-> media_id = request('media_id');
         $model-> event_name = request('event_name');
         $model-> event_url = request('event_url');
         $model-> page_url = request('page_url');
         $model-> reg_date = date( 'Y-m-d H:i:s', time() );
         
         $model->save();
//        DB::table('event')->insert(
//            ['advertiser_id' => request('adv_id'), 'media_id' => request('media_id') , 'event_name' => request('event_name') , 'event_url' => request('event_url') , 'page_url' => request('page_url'), 'reg_date'=>'now()']
//        );
         Alert::success('성공', '이벤트가 생성되었습니다');
        return redirect('event');

    }
    //
}
