<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/png" sizes="16x16" href="{!! asset('theme/plugins/images/favicon.png') !!}">
<title>Login Plataforma - ERPSoft Predial</title>
<!-- Bootstrap Core CSS -->
<link href="{!! asset('theme/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
<!-- animation CSS -->
<link href="{!! asset('theme/css/animate.css') !!}" rel="stylesheet">
<!-- Custom CSS -->
<link href="{!! asset('theme/css/style.css') !!}" rel="stylesheet">
<!-- color CSS -->
<link href="{!! asset('theme/css/colors/green.css') !!}" id="theme"  rel="stylesheet">
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="{!! asset('theme/css/customcss/style.css') !!}" rel="stylesheet">

</head>
<body>
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="login-register">
  <div class="login-box login-sidebar" style="opacity: 0.8">
    <div class="white-box" style="background: transparent;">
        <!-- <h3 style="text-align: center;">Plataforma - Predial</h3><br /> -->
      <form class="form-horizontal form-material" id="loginform" method="post" action="{{ route('auth.checkLogin') }}">
        <a href="javascript:void(0)" class="text-center db">
            <img src="{!! asset('theme/plugins/images/admin-logo-dark.png') !!}" alt="Home" />
            {{-- <i class="ti-home" style="font-size: 250%;"></i> --}}
            <br/>
            <!-- <img src="{!! asset('theme/plugins/images/admin-text-dark.png') !!}" alt="Home" /> -->
            <h3 style="text-align: center;"><b>ERP</b>Soft Predial</h3>
        </a>
        <h3 class=" m-t-40" style="text-align: center;">Inicio de sesi&oacute;n</h3><br />
        @csrf
        <div class="result">
            @if(Session::get('fail'))
                <div class="alert alert-danger">
                    {!! Session::get('fail') !!}
                </div>
            @endif
        </div>
        <div class="form-group m-t-40">
          <div class="col-xs-12">
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email" autocomplete="off">
            <span class="text-danger">@error('email') {{ $message }} @enderror</span>
          </div>
        </div>
        <div class="form-group">
          <div class="col-xs-12">
            <input type="password" name="password" class="form-control" placeholder="Contrase&ntilde;a">
            <span class="text-danger">@error('password') {{ $message }} @enderror</span>
          </div>
        </div>
        <div class="form-group">
          <div class="col-md-12">
            {{-- <div class="checkbox checkbox-primary pull-left p-t-0">
              <input id="checkbox-signup" type="checkbox">
              <label for="checkbox-signup"> Remember me </label>
            </div> --}}
            {{-- <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right">
                <i class="fa fa-lock m-r-5"></i> ¿Olvido su contrase&ntilde;a?
            </a> --}}
        </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button name="login" class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Ingresar</button>
          </div>
        </div>
        <!-- <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
            <div class="social"><a href="javascript:void(0)" class="btn  btn-facebook" data-toggle="tooltip"  title="Login with Facebook"> <i aria-hidden="true" class="fa fa-facebook"></i> </a> <a href="javascript:void(0)" class="btn btn-googleplus" data-toggle="tooltip"  title="Login with Google"> <i aria-hidden="true" class="fa fa-google-plus"></i> </a> </div>
          </div>
        </div>
        <div class="form-group m-b-0">
          <div class="col-sm-12 text-center">
            <p>Don't have an account? <a href="register2.html" class="text-primary m-l-5"><b>Sign Up</b></a></p>
          </div>
        </div> -->
      </form>
      <form class="form-horizontal" id="recoverform" action="index.html">
        <div class="form-group ">
          <div class="col-xs-12">
            <h3>Recover Password</h3>
            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
          </div>
        </div>
        <div class="form-group ">
          <div class="col-xs-12">
            <input class="form-control" type="text" required="" placeholder="Email">
          </div>
        </div>
        <div class="form-group text-center m-t-20">
          <div class="col-xs-12">
            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>
<!-- jQuery -->
<script src="{!! asset('theme/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{!! asset('theme/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<!-- Menu Plugin JavaScript -->
<!-- <script src="{!! asset('theme/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}"></script> -->

<!--slimscroll JavaScript -->
<!-- <script src="{!! asset('theme/js/jquery.slimscroll.js') !!}"></script> -->
<!--Wave Effects -->
<!-- <script src="{!! asset('theme/js/waves.js') !!}"></script> -->
<!-- Custom Theme JavaScript -->
<!-- <script src="{!! asset('theme/js/custom.min.js') !!}"></script> -->
<!--Style Switcher -->
<!-- <script src="{!! asset('theme/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script> -->
<!-- Laravel Javascript Validation -->
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
{!! JsValidator::formRequest('App\Http\Requests\LoginFormRequest', '#loginform'); !!}
<script>
    $(document).ready(function() {
        $(function() {
            $(".preloader").fadeOut();
        });
    });
</script>
</body>
</html>
