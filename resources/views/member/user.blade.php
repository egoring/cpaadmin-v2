@extends('member.layout')
 @section('main')
  
 <body>
  <!--main content start-->
    <section id="main-content">
      <section class="wrapper">
        <div class="row">
          <div class="col-lg-12">
            <h3 class="page-header"><i class="fa fa-table"></i> Table</h3>
            <ol class="breadcrumb">
              <li><i class="fa fa-home"></i><a href="{{ url('list') }}">Home</a></li>
              <li><i class="fa fa-table"></i>Table</li>
              <li><i class="fa fa-th-list"></i>Basic Table</li>
            </ol>
          </div>
        </div>
        
        <div class="row">
          <div class="col-lg-12">
            <section class="panel">
              <header class="panel-heading">
                Advanced Table <?php echo date( 'Y-m-d H:i:s', time() );?>
                @if ((Auth::guard('member')->user()->grade) === 9)
                <a class="btn btn-primary" href="user/create" style="float:right;">계정 생성</a>
                @endif
              </header>

              <table class="table table-striped table-advance table-hover">
                <tbody>
                
                  <tr>
                    <th><i class="icon_profile"></i> No</th>
                    <th><i class="icon_calendar"></i> 분류</th>
                    <th><i class="icon_mail_alt"></i> 아이디</th>
                    <th><i class="icon_pin_alt"></i> 이름</th>
                    <th><i class="icon_mobile"></i> 연락처</th>
                    <th><i class="icon_cogs"></i> 업체명</th>
                      @if ((Auth::guard('member')->user()->grade) === 9)
                    <th><i class="icon_cogs"></i> 광고상태</th>
                    @endif
                  </tr>
                  
                  @foreach ($users as $user)
                   <form name="userstate" class="navbar-form" action="/user/{{ $user->id }}/state" method="POST">
                   {{ csrf_field() }}
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                    @if(($user->grade) === 1)
                    광고주
                    @elseif(($user->grade) === 2)
                    매체
                    @else 
                    관리자
                    @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->tel }}</td>
                    <td>{{ $user->name }}</td>
                      @if ((Auth::guard('member')->user()->grade) === 9)
                    <td>
                          <select name="adconfirm_ck" class="form-control m-bot10" style="padding-left:0; background:none;" onchange="this.form.submit();">
                          <option value="1"  @if(($user->adconfirm) === 1) selected @endif> OFF</option>
                          <option value="2"  @if(($user->adconfirm) === 2) selected @endif> ON</option>
                        </select>
                        </td>
                        @endif
                  </tr>
                    </form>
                  @endforeach
                  
                  
                </tbody>
              </table>
            </section>
          </div>
        </div>
        <!-- page end-->
        {{ $users->render() }}
      </section>
    </section>
</body>
@endsection()
