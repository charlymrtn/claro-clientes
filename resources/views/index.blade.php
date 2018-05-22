<!doctype html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>ClaroPay - Clientes</title>

        <script type="text/javascript" src="{{ mix('/js/mix/ui.js') }}"></script>
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/ui.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/vendor.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('css/login.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/plugins/iCheck/square/blue.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/adminlte/css/auth.css') }}">

</head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="links">
                    Claro Pagos
                </div>
                <div class="title m-b-md">
                    Clientes
                </div>
                <div class="card card-container">
                    <p class="login-box-msg">{{ trans('adminlte::adminlte.login_message') }}</p>
                    <form class="form-signin" action="{{ url(config('adminlte.login_url', 'login')) }}" method="post">
                        <span id="reauth-email" class="reauth-email"></span>

                        <div class="form-group has-feedback {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input id="inputEmail" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('adminlte::adminlte.email') }}" required autofocus>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input id="inputPassword" type="password" name="password" class="form-control" placeholder="{{ trans('adminlte::adminlte.password') }}" required>
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div id="remember" class="remember-me checkbox">
                            <label>
                                <input name="remember" type="checkbox" value="remember-me"> {{ trans('adminlte::adminlte.remember_me') }}
                            </label>
                        </div>
                        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">{{ trans('adminlte::adminlte.sign_in') }}</button>
                        {!! csrf_field() !!}
                    </form>
                    <div class="auth-links">
                        <a href="{{ url(config('adminlte.password_reset_url', 'password/reset')) }}" class="forgot-password text-center">{{ trans('adminlte::adminlte.i_forgot_my_password') }}</a>
                        <br>
                        @if (config('adminlte.register_url', 'register'))
                            <a href="{{ url(config('adminlte.register_url', 'register')) }}"
                               class="text-center"
                            >{{ trans('adminlte::adminlte.register_a_new_membership') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- Scripts -->
        <script src="{{ asset('vendor/adminlte/plugins/iCheck/icheck.min.js') }}"></script>
        <script>
            $(function () {
                $('input').iCheck({
                    checkboxClass: 'icheckbox_square-blue',
                    radioClass: 'iradio_square-blue',
                    increaseArea: '20%' // optional
                });
            });
        </script>
    </body>
</html>