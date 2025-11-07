<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ app_name() }} | Legal Team Registration</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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

<div class="login-box" style="width: 530px;">
    <div class="login-logo">
        <a href="{{ route('frontend.index') }}"><img src="{{ asset('img/logo-white.png') }}"></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Sign up below</p>

            @include('includes.partials.messages')

                {{ html()->form('POST', route('frontend.auth.register.post'))->open() }}
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" name="first_name" maxlength="191" class="form-control"
                                   placeholder="First Name" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" name="last_name" maxlength="191" class="form-control"
                                   placeholder="Last Name" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-alt"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" name="firm" maxlength="191" class="form-control"
                                   placeholder="Firm Name" required>
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-users"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-3">
                            <input type="text" name="principal_partner_contact" maxlength="191" class="form-control"
                                   placeholder="Principal Partner Contact">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-user-alt"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" name="email" maxlength="191" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <small class="text-muted">Double check this email, as One time passwords (OTP) will be sent to it to allow you sign in.</small>
                </div>
            @include('includes.partials._hidden-user-reg-password-field')

                <div class="row">
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn bg-maroon btn-block">Sign Up</button>
                    </div>
                    <!-- /.col -->
                </div>
                {{ html()->form()->close() }}



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
