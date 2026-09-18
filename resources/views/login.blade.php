@extends('member.layout')
 @section('content')
  

  <body class="login-img3-body">

  <div class="container">

    <form class="login-form" action="{!!url('login')!!}" method="post">
     {{csrf_field()}}
      <div class="login-wrap">
        <p class="login-img"><i class="icon_lock_alt"></i></p>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon_profile"></i></span>
          <input type="text" id="userId" name="email" class="form-control" placeholder="Username" autofocus>
        </div>
        <div class="input-group">
          <span class="input-group-addon"><i class="icon_key_alt"></i></span>
          <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <label class="checkbox">
                <input type="checkbox" id="idSaveCheck" value="remember-me"> Remember me
<!--                <span class="pull-right"> <a href="#"> Forgot Password?</a></span>-->
            </label>
        <button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
<!--        <button class="btn btn-info btn-lg btn-block" type="submit">Signup</button>-->
      </div>
    </form>
    <div class="text-right">
      <div class="credits">
      
<!--          Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>-->
        </div>
    </div>
  </div>
</body>

@endsection()