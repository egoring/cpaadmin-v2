@extends('member.layout')
 @section('main')
  
 <body>
  <!-- container section start -->
<!--  <section id="container" class="">-->

    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
  
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                DB 관리 (오늘 날짜 : <span id="today"></span>)
              </header>
              <div  class="panel-heading" style="min-height:40px;">
            <form name="help_li" class="navbar-form" action="/list" method="GET">
             {{csrf_field()}}
            <div class="col-lg-5">
            @if ((int) Auth::guard('member')->user()->grade >= 9)
            <?php $tval = DB::table('users')->orderBy('username','desc')->get();?>
            <select name ="sadver" class="form-control m-bot10" style="padding-left:0; background:none; width:150px; height:35px; " onchange="call_proc6(help_li);">
                 <option value="0"> 전체보기</option>
                 @foreach ($tval as $ser)
                 <option value="{{$ser->id}}"
                 @if ($ser->id == request('sadver') )selected @endif >
                 {{$ser->username}} / {{$ser->id}}</option>
                 @endforeach()
             </select>
            @endif
             
             <select name ="smode" class="form-control m-bot10" style="padding-left:0; background:none; width:150px;  height:35px;">
                 <option value="1" @if ( request('smode') == 1 )
                selected @endif> 매체코드</option>
                 <option value="2" @if ( request('smode') == 2 )
                selected @endif> 업체명</option>
                 <option value="3" @if ( request('smode') == 3 )
                selected @endif> 이름</option>
                 <option value="4" @if ( request('smode') == 4 )
                selected @endif> 전화번호</option>
             </select>
             
              <input class="form-control m-bot10" placeholder="Search" type="text" name="search" value="{{ old('search') ? old('search') : request('search') }}">
                </div>
                <div class="col-lg-7">

                 시작일: <input autocomplete="false" class="form-control" type="text" id="fromDate" name="start_date" value="{{ old('start_date') ? old('start_date') : request('start_date') }}" >
                 종료일: <input  autocomplete="false"  class="form-control" type="text" id="toDate" name="end_date"  value="{{ old('end_date') ? old('end_date') : request('end_date') }}" >
                 
                 
                 <button type="submit" class="btn btn-primary" style="">검색</button>
                 
                 <select name="recordPerPage" class="form-control m-bot10" style="padding-left:0; background:none; width:80px;  height:35px;" onchange="call_proc6(help_li);">
                     <option value="15" @if ( request('recordPerPage') == 15 )
                selected @endif>15줄</option>
                     <option value="30" @if ( request('recordPerPage') == 30 )
                selected @endif>30줄</option>
                     <option value="50" @if ( request('recordPerPage') == 50 )
                selected @endif>50줄</option>
                     <option value="100" @if ( request('recordPerPage') == 100 )
                selected @endif>100줄</option>
                 </select>
                 <a class="btn btn-primary" href="list">검색 초기화</a>
                </div>
                </form>

                </div>

              <table class="table table-striped table-advance table-hover">
                <tbody>
                  <tr>
                   <th><i class="icon_profile"></i> No</th>
                    <th><i class="icon_profile"></i> 이벤트 이름</th>
                    <th><i class="icon_pin_alt"></i> 아이피</th>
                    <th><i class="icon_mail_alt"></i> 이름</th>
                    <th><i class="icon_mobile"></i> 전화번호</th>
                    <th><i class="icon_calendar"></i> 입력일</th>
                    <th><i class="icon_calendar"></i> 매체코드</th>

                    <th><i class="icon_cogs"></i> Action</th>
                  </tr>
                    @foreach ($member as $user)
                    <tr>
                    <td>{{ $user->msn }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->bIp }}</td>
                    <td>{{ $user->bName }}</td>
                    <td>{{ $user->bPhone }}</td>
                    <td>{{ $user->inputDate }}</td>
                    <td>{{ $user->media_code }}</td>
                    
                    

                    <td>
                      <div class="btn-group">
        
                       <!-- 기본 모달 -->
                       <div class="modal fade" id="myModal-{{ $user->msn }}" tabindex="{{ $user->msn }}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                       <div class="modal-content">
                       <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                       <h4 class="modal-title" id="myModalLabel">상담내용</h4>
                       </div>
                       <div class="modal-body">
                       <span>{{ $user->bmemo }}</span>
                       </div>
                       <div class="modal-footer">
                       <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button></div>
                       </div>
                       </div>
                       </div>
                       <!--모달끝-->
                        <a class="btn btn-primary" data-toggle="modal" data-target="#myModal-{{ $user->msn }}" ><i class="icon_plus_alt2"></i></a>
<!--                        <a class="btn btn-success" href="#"><i class="icon_check_alt2"></i></a>-->
                       
                       <!-- 기본 모달 -->
                       <div class="modal fade" id="myModal_del-{{ $user->msn }}" tabindex="{{ $user->msn }}" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                       <div class="modal-dialog">
                       <div class="modal-content">
<!--
                       <div class="modal-header">
                       <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                       
                       </div>
-->
                       <div class="modal-body">
                       <h4 class="modal-title" id="myModalLabel">삭제 하시겠습니까?</h4>
                       </div>
                       
                       <div class="modal-footer">
                       <a class="btn btn-danger" href="/list/del/{{$user->msn}}">삭제</a>
                       
                       <button type="button" class="btn btn-default" data-dismiss="modal">닫기</button></div>
                       </div>
                       </div>
                       </div>
                       <!--모달끝-->
                        
                        

                        <a class="btn btn-danger" data-toggle="modal" data-target="#myModal_del-{{ $user->msn }}"  ><i class="icon_close_alt2"></i></a>
                      </div>
                    </td>
                  </tr>
                  @endforeach
                 
                  
                </tbody>
              </table>
            </section>
          </div>
        </div>
        <!-- page end-->
<div style="text-align:center;">
                {{ $member->render() }}
          </div>
                   <div class="col-lg-6" style="text-align:center;"  >
                   <div class="col-lg-2">
                    <form name="help_li2" class="navbar-form" action="/list/exceldown" method="get">
                      {{csrf_field()}}
                       <button type="submit" class="btn btn-primary" style="">엑셀 다운로드</button>
                       <input type="hidden" name="_token" value="{{request('_token')}}">
                       <input type="hidden" name="sadver" value="{{request('sadver')}}">
                       <input type="hidden" name="smode" value="{{request('smode')}}">
                        <input type="hidden" name="search" value="{{request('search')}}">
                        <input type="hidden" name="start_date" value="{{request('start_date')}}">
                        <input type="hidden" name="end_date"value="{{request('end_date')}}">

                   </form>
                       </div>
                        <div class="col-lg-2">
                   <form name="help_li2" class="navbar-form" action="/list/csvdown" method="get">
                      {{csrf_field()}}
                       <button type="submit" class="btn btn-primary" style="">CSV 다운로드</button>
                       <input type="hidden" name="_token" value="{{request('_token')}}">
                       <input type="hidden" name="sadver" value="{{request('sadver')}}">
                       <input type="hidden" name="smode" value="{{request('smode')}}">
                        <input type="hidden" name="search" value="{{request('search')}}">
                        <input type="hidden" name="start_date" value="{{request('start_date')}}">
                        <input type="hidden" name="end_date"value="{{request('end_date')}}">

                   </form>
                       </div>
          </div>
<!--              <a class="btn btn-danger" href="/list/exceldown">export</a>-->
                    
             
      </section>
    </section>
    <!--main content end-->
    <div class="text-right">
      <div class="credits">
        
        </div>
    </div>
<!--  </section>-->
  <!-- container section end -->
  <!-- javascripts -->
<!--
  <script src="js/jquery.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/jquery.scrollTo.min.js"></script>
  <script src="js/jquery.nicescroll.js" type="text/javascript"></script>
  <script src="js/scripts.js"></script>
-->

   
<!--
   <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
   
-->
   


</body>
@endsection()
