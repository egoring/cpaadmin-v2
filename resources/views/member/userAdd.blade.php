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
              <li><i class="fa fa-home"></i><a href="{{ url('list') }}">Home</a></li>
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
                  <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="/user">
                   {{ csrf_field() }}
                   <div class="form-group ">
                      <label for="grade" class="control-label col-lg-2">분류 <span class="required">*</span></label>
                      <div class="col-lg-10">
                      <select name="grade" class="form-control m-bot10" style="padding-left:0; background:none; " >
                            <option value="1">광고주</option>
                            <option value="2">매체</option>
                            @if ((Auth::guard('member')->user()->grade) === 9)
                            <option value="9">관리자</option>
                            @endif
                        </select>
                      </div>
                    </div>
                    
                    <div class="form-group ">
                      <label for="uid" class="control-label col-lg-2">아이디 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" id="uid" name="uid" minlength="4" type="text" required />
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="password" class="control-label col-lg-2">패스워드 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control " id="password" type="password" name="password" required />
                      </div>
                    </div>
<!--
                    <div class="form-group ">
                      <label for="password_confirmation" class="control-label col-lg-2">패스워드 확인</label>
                      <div class="col-lg-10">
                        <input class="form-control " id="password_confirmation" type="password" name="password_confirmation" />
                      </div>
                    </div>
-->
                    <div class="form-group ">
                      <label for="username" class="control-label col-lg-2">이름 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" id="username" name="username" minlength="2" type="text" required />
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="company" class="control-label col-lg-2">회사명</label>
                      <div class="col-lg-10">
                        <input class="form-control" id="company" name="company" minlength="2" type="text" required />
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="tel" class="control-label col-lg-2">연락처</label>
                      <div class="col-lg-10">
                        <input class="form-control" id="tel" name="tel" minlength="2" type="text" required />
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
