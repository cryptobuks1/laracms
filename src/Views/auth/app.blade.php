<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('vendor/laraveladmin/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laraveladmin/lib/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laraveladmin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laraveladmin/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laraveladmin/lib/source-sans-pro.css') }}">
    @stack('css')
</head>
<body class="hold-transition login-page">
    @yield('content')
    <script src="{{ asset('vendor/laraveladmin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/laraveladmin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/laraveladmin/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
</body>
</html>
