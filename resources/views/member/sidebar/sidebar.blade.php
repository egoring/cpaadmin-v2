<aside>
      <div id="sidebar" class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu">
          <li class="active">
            <a class="" href="{!!url('list')!!}">
                          <i class="icon_house_alt"></i>
                          <span>Dashboard</span>
                      </a>
          </li>
          <li class="sub-menu">
            <a  class="">
                          <i class="icon_document_alt"></i>
                          <span>광고주 메뉴</span>
                          <span class="menu-arrow arrow_carrot-right"></span>
                      </a>
            <ul class="sub" style="display:block">
              <li><a class="" href="{!!url('user')!!}">계정 관리</a></li>
              <li><a class="" href="{!!url('event')!!}">이벤트 관리</a></li>
              <li><a class="" href="{!!url('list')!!}">DB 관리</a></li>
              
            </ul>
          </li>
          @if ((Auth::guard('member')->user()->grade) === 2)
          <li class="sub-menu">
            <a  class="">
                          <i class="icon_desktop"></i>
                          <span>매체 메뉴</span>
                          <span class="menu-arrow arrow_carrot-right"></span>
                      </a>
            <ul class="sub" style="display:block">
              <li><a class="" href="{!!url('list')!!}">DB 관리</a></li>
             
            </ul>
          </li>
          @endif
<!--
          <li>
            <a class="" href="widgets.html">
                          <i class="icon_genius"></i>
                          <span>Widgets</span>
                      </a>
          </li>
          <li>
            <a class="" href="chart-chartjs.html">
                          <i class="icon_piechart"></i>
                          <span>Charts</span>

                      </a>

          </li>
-->

           @if ((Auth::guard('member')->user()->grade) === 9)
          <li class="sub-menu">
            <a class="">
                          <i class="icon_table"></i>
                          <span>관리자 메뉴</span>
                          <span class="menu-arrow arrow_carrot-right"></span>
                      </a>
            <ul class="sub" style="display:block">
              <li><a class="" href="{!!url('list')!!}">DB 관리</a></li>
              <li><a class="" href="{!!url('user/create')!!}">계정 추가</a></li>
                <li><a class="" href="{!!url('userPwd')!!}">계정 비밀번호 변경</a></li>
            </ul>
          </li>
          @endif

<!--
          <li class="sub-menu">
            <a href="javascript:;" class="">
                          <i class="icon_documents_alt"></i>
                          <span>Pages</span>
                          <span class="menu-arrow arrow_carrot-right"></span>
                      </a>
            <ul class="sub">
              <li><a class="" href="profile.html">Profile</a></li>
              <li><a class="" href="login.html"><span>Login Page</span></a></li>
              <li><a class="" href="contact.html"><span>Contact Page</span></a></li>
              <li><a class="" href="blank.html">Blank Page</a></li>
              <li><a class="" href="404.html">404 Error</a></li>
            </ul>
          </li>
-->

        </ul>
        <!-- sidebar menu end-->
      </div>
    </aside>
    <!--sidebar end-->