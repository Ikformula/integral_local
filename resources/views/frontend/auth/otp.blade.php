<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ app_name() }} | OTP</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('adminlte3.2/css/adminlte.min.css') }}">

    <style>
        body {
            background: url(https://res.cloudinary.com/ikformula/image/upload/v1675251629/Arik/ji-seongkwang-XPg3xqFpPgc-unsplash_1.jpg) no-repeat center center fixed;
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
      <p class="login-box-msg">OTP Input</p>

        @include('includes.partials.messages')

      <div class="social-auth-links text-center mb-3">
          <p class="text-sm"> Verify your One Time Password sent to {{ session()->get('email') }}</p>
              {{ html()->form('POST', route('frontend.auth.verify.otp'))->open() }}
                <input type="hidden" name="email" value="{{ session()->get('email') }}">
              <div class="input-group mb-3">
                  <input type="text" name="otp" maxlength="6" class="form-control" placeholder="">
                  <div class="input-group-append">
                      <div class="input-group-text">
                          <span class="fas fa-lock"></span>
                      </div>
                  </div>
              </div>
              <div class="mt-2">
                  <button type="submit" class="btn bg-maroon float-right">Verify</button>
              </div>
              {{ html()->form()->close() }}
{{--          @include('frontend.auth.includes.socialite')--}}
          <div class="mt-3">
              <a href="{{ route('frontend.auth.login') }}" class="btn btn-link">Resend OTP</a>
          </div>
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
