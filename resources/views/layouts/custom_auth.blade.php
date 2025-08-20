<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="https://hyper-admin-templates.multipurposethemes.com/bs5/images/favicon.ico">

    <title>{{ config('app.name', 'Organizaion Admin panel') }} </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/skin_color.css') }}">
    @stack('styles')
</head>

<body class="hold-transition theme-primary bg-img" style="background-image: url('{{ asset('hyper/images/auth-bg/bg-16.jpg') }}');">>

    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Vendor JS -->
    <script src="{{ asset('hyper/template/vertical/src/js/vendors.min.js') }}">
    </script>
    <script src="{{ asset('hyper/template/vertical/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('hyper/assets/icons/feather-icons/feather.min.js') }}"></script>
    @stack('scripts')

</body>

</html>