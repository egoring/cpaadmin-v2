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
                <a class="btn btn-primary" href="event/create" style="float:right;">이벤트 생성</a>
              </header>

              <table class="table table-striped table-advance table-hover">
                <tbody>
                  <tr>
                    <th><i class="icon_profile"></i> No</th>
                    <th><i class="icon_calendar"></i> 이벤트코드</th>
                    <th><i class="icon_mail_alt"></i> 이벤트명</th>
                    <th><i class="icon_pin_alt"></i> URL</th>
                    <th><i class="icon_mobile"></i> 매체명</th>
                    <th><i class="icon_cogs"></i> 광고주</th>
                    <th><i class="icon_cogs"></i> 디렉토리</th>
                  </tr>
                  
                  @foreach ($event as $user)
                  <tr>
                    <td>{{ $user->esn }}</td>
                    <td>{{ $user->advertiser_id }}</td>
                    <td>{{ $user->event_name }}</td>
                    <td>{{ $user->event_url }}</td>
                    <td>{{ $user->me_co_name }}</td>
                    <td>{{ $user->ac_co_name }}</td>
                    <td>{{ $user->page_url }}</td>
<!--
                    <td>
                      <div class="btn-group">
                        <a class="btn btn-primary" href="#"><i class="icon_plus_alt2"></i></a>
                        <a class="btn btn-success" href="#"><i class="icon_check_alt2"></i></a>
                        <a class="btn btn-danger" href="#"><i class="icon_close_alt2"></i></a>
                      </div>
                    </td>
-->
                  </tr>
                  @endforeach
                  
                  
                </tbody>
              </table>
            </section>
          </div>
        </div>
        <!-- page end-->
        
        {{ $event->render() }}
      </section>
    </section>
</body>
@endsection()
