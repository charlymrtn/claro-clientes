<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'Claro Pagos'))
@yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/mix/ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/vendor.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
    @yield('adminlte_css')
    <script type="text/javascript" src="{{ mix('/js/mix/ui.js') }}"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')

<script type="text/javascript" src="{{ mix('js/vendor.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
<script type="text/javascript" src="{{ asset('vendor/adminlte/dist/js/app.min.js') }}"></script>
<script type="text/javascript">
    window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};
    jQuery(document).ready(function($) {
        PNotify.prototype.options.styling = "bootstrap3";
        PNotify.prototype.options.styling = "fontawesome";
        @foreach (Alert::getMessages() as $type => $messages)
            @foreach ($messages as $message)
                $(function(){ new PNotify({ text: "{{ $message }}", type: "{{ $type }}", icon: true }); });
            @endforeach
        @endforeach
    });
</script>
@yield('adminlte_js')

</body>
</html>
