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
                  <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="/userPwd">
                   {{ csrf_field() }}
                   <div class="form-group ">
                      <label for="grade" class="control-label col-lg-2">분류 <span class="required">*</span></label>
                      <div class="col-lg-10">
                      <?php $tval = DB::table('users')->orderBy('id','desc')->get();?>
                      <select name ="sadver" class="form-control m-bot10" style="padding-left:0; background:none; width:150px; height:35px; " onchange="call_proc6(help_li);">
                 <option value="0"> 전체보기</option>
                 @foreach ($tval as $ser)
                 <option value="{{$ser->id}}" 
                 @if ($ser->id == request('sadver') )selected @endif > 
                 {{$ser->email}} / {{$ser->id}}</option>
                 @endforeach()
             </select>
                      </div>
                    </div>
                    
                    
                    <div class="form-group ">
                      <label for="password" class="control-label col-lg-2">패스워드 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control " id="password" type="password" name="password" required />
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
