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
                  <form class="form-validate form-horizontal" id="feedback_form" method="POST" action="/userPwd">
                   {{ csrf_field() }}
                   @if (count($accounts))
                   <div class="form-group ">
                      <label for="sadver" class="control-label col-lg-2">대상 계정</label>
                      <div class="col-lg-10">
                      <select name="sadver" id="sadver" class="form-control m-bot10" style="padding-left:0; background:none; width:250px; height:35px; ">
                        <option value="0">본인 계정</option>
                        @foreach ($accounts as $ser)
                        <option value="{{ $ser->id }}">{{ $ser->email }} / {{ $ser->id }}</option>
                        @endforeach
                      </select>
                      <span class="help-block">본인 계정을 바꿀 때는 현재 비밀번호가 필요합니다.</span>
                      </div>
                    </div>
                    @endif

                    <div class="form-group ">
                      <label for="current_password" class="control-label col-lg-2">현재 패스워드</label>
                      <div class="col-lg-10">
                        <input class="form-control" id="current_password" type="password" name="current_password" autocomplete="current-password" />
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="password" class="control-label col-lg-2">새 패스워드 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" id="password" type="password" name="password" minlength="8" autocomplete="new-password" required />
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="password_confirmation" class="control-label col-lg-2">새 패스워드 확인 <span class="required">*</span></label>
                      <div class="col-lg-10">
                        <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" minlength="8" autocomplete="new-password" required />
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
