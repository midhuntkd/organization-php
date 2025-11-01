<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{ asset('hyper/images/favicon.ico') }}">

    <title>{{ config('app.name', 'Organizaion Admin panel') }} </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/vendors_css.css') }}">

    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('hyper/template/vertical/src/css/skin_color.css') }}">
    @stack('styles')
</head>

<body class="hold-transition dark-skin theme-primary bg-img">

    <style>
        .logo-div img {
            max-width: 400px;
        }
    </style>

    <div class="h-p100">
        <div class="row justify-content-md-center min-h-p100 py-4" style="background-image: url('{{ asset('hyper/images/auth-bg/bg-16.jpg') }}')">

            <div class="col-12">
                <div class="row g-0 h-p100 justify-content-center align-items-lg-center">
                    <div class="col-lg-6 h-p100 align-items-lg-center d-lg-flex justify-content-md-center d-none logo-div">
                        <a href="#" target="_blank" style="text-decoration: none;">
                            <img src="https://member.org.in/storage/org/l-logo.png" alt="Hyper Admin">
                        </a>
                    </div>
                    <div class="col-lg-6 align-items-center d-flex h-p100 flex-column flex-lg-row px-3 logo-div">
                        <div class="py-20 text-center justify-content-center d-lg-none">
                            <a href="#" target="_blank" style="text-decoration: none;">
                                <img src="" alt="Hyper Admin">
                            </a>
                        </div>
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