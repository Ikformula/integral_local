<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ app_name() }} | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/css/adminlte.min.css') }}">

    <style>
        @if(isset($_GET['legal']))
        @php
        $mode = 'legal';
        $bg_img = 'https://res.cloudinary.com/anya-ng/image/upload/c_scale,h_1000,w_1500/asadal_stock_80_axsijm';
        @endphp
        @else
        @php $bg_img = 'https://res.cloudinary.com/ikformula/image/upload/v1675251629/Arik/ji-seongkwang-XPg3xqFpPgc-unsplash_1.jpg'; @endphp
        @endif

        body {
            background: url({{ $bg_img }}) no-repeat center center fixed;
            background-size: cover;
            height: 100%;
            overflow: hidden;
        }
    </style>
</head>
<body class="dark-mode hold-transition login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="{{ route('frontend.index') }}"><img src="{{ asset('img/logo-white.png') }}"></a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

        @include('includes.partials.messages')
        @if(isset($_GET['show_form']) && $_GET['show_form'] == 1)
            {{ html()->form('POST', route('frontend.auth.login.post'))->open() }}
        <div class="input-group mb-3">
          <input type="email" name="email" maxlength="191" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" maxlength="191" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember" value="1" checked="checked">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn bg-maroon btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
            {{ html()->form()->close() }}
        @endif

      <div class="social-auth-links text-center mb-3">
          <p class="text-sm"> Enter your email address to login using a One Time Password (OTP) </p>

              {{ html()->form('POST', route('frontend.auth.send.otp'))->open() }}

              <div class="input-group mb-3">
                  <input type="email" name="email" maxlength="191" class="form-control" placeholder="janedoe@arikair.com" value="{{ session()->has('email') ? session()->get('email') : (isset($mode) && $mode == 'legal' ? '' : '@arikair.com')}}">
                  <div class="input-group-append">
                      <div class="input-group-text">
                          <span class="fas fa-envelope"></span>
                      </div>
                  </div>
              </div>
              <div class="mt-2">
                  <button type="submit" class="btn bg-maroon float-right">Send OTP</button>
              </div>
              {{ html()->form()->close() }}
{{--          @include('frontend.auth.includes.socialite')--}}
      </div>
      <!-- /.social-auth-links -->


    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script src="{{ asset('adminlte3.2/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('adminlte3.2/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('adminlte3.2/js/adminlte.js') }}"></script>
</body>
</html>
