<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf_token" value="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- css -->
    <link rel="stylesheet" href="{{ asset('vendor/laracms/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laracms/lib/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laracms/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laracms/lib/source-sans-pro.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laracms/app/css/skin.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/laracms/app/css/app.css') }}">
    @stack('css')
    <!-- js -->
    <script src="{{ asset('vendor/laracms/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/laracms/lib/variables.js') }}"></script>
    <script type="text/javascript">var current_url = "{{ url()->current() }}";</script>
    @isset($current_url)
    <script type="text/javascript">var current_url = "{{ (string) $current_url }}";</script>
    @endisset
</head>
<body class="hold-transition sidebar-mini layout-navbar-fixed text-sm">
    <div class="wrapper">
        @include('laracms::components.topnav')
        @include('laracms::components.sidebar')
        <div class="content-wrapper">
            <!-- content header -->
            <section class="content-header">
                <div class="container-fluid form-inline">
                    <h1 class="mb-2 mr-md-3">@yield('title')</h1>
                    @yield('header')
                </div>
            </section>

            <!-- content -->
            <section class="content">
                <div class="container-fluid">
                    @include('laracms::components.notify')
                    @yield('content')
                </div>
            </section>
        </div>
        @include('laracms::components.footer')
    </div>

    <!-- js -->
    <script src="{{ asset('vendor/laracms/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/laracms/dist/js/adminlte.min.js') }}"></script>
    <script src="{{ asset('vendor/laracms/app/js/skin.js') }}"></script>
    <script src="{{ asset('vendor/laracms/app/js/app.js') }}"></script>
    @stack('js')
</body>
</html>