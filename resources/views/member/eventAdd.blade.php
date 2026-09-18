@extends('member.layout')
 @section('main')
    <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-files-o"></i> 계정 추가</h3>
<!--
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="index.html">Home</a></li>
              <li><i class="icon_document_alt"></i>Forms</li>
              <li><i class="fa fa-files-o"></i>Form Validation</li>
            </ol>
-->
          </div>
        </div>
        <!-- Form validations -->
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Form validations
              </header>
              <div class="panel-body">
                <div class="form">
                  <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="/event">
                   {{csrf_field()}}
                    <div class="form-group ">
                      <label for="cname" class="control-label col-lg-2">광고주명 <span class="required">*</span></label>
                      <div class="col-lg-10">
                       <?php 
                          if(Auth::guard('member')->user()->grade < 9) {
                              $tval = DB::table('users')->where('id','=',Auth::guard('member')->user()->id)->get();
                          }
                          else {
                              $tval = DB::table('users')->where('grade','=','1')->orderBy('id','desc')->get();
                          }
                        
                          ?>
                        <select name="adv_id" class="form-control m-bot10" style="padding-left:0; background:none; " >
<!--                            <option value="0"> 전체보기</option>-->
                            @foreach ($tval as $ser)
                            <option value="{{$ser->id}}">
                                {{$ser->username}} / {{$ser->id}}</option>
                            @endforeach()
                        </select>
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="cemail" class="control-label col-lg-2">매체명 <span class="required">*</span></label>
                      <div class="col-lg-10">
                       <?php $tval2 = DB::table('users')->where('grade','=','2')->orderBy('username','asc')->get();?>
                        <select name="media_id" class="form-control m-bot10" style="padding-left:0; background:none;" >
<!--                            <option value="0"> 전체보기</option>-->
                            @foreach ($tval2 as $ser2)
                            <option value="{{$ser2->id}}">
                                {{$ser2->username}} / {{$ser2->id}}</option>
                            @endforeach()
                        </select>
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="curl" class="control-label col-lg-2">이벤트명</label>
                      <div class="col-lg-10">
                        <input class="form-control " id="curl" type="text" name="event_name" />
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="cname" class="control-label col-lg-2">URL <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" id="subject" name="event_url" minlength="5" type="text" required />
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="ccomment" class="control-label col-lg-2">디렉토리</label>
                      <div class="col-lg-10">
                        <textarea class="form-control " id="ccomment" name="page_url" required></textarea>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-offset-2 col-lg-10">
                        <button class="btn btn-primary" type="submit">Save</button>
                        <button class="btn btn-default" type="button">Cancel</button>
                         
                      </div>
                    </div>
                  </form>
                </div>

              </div>
            </section>
          </div>
        </div>
        
        <!-- page end-->
      </section>
    </section>

@endsection()
