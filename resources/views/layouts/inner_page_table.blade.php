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

<body class="hold-transition light-skin sidebar-mini theme-primary dark-skin fixed">

    <div class="wrapper">
        <div id="loader"></div>
        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Left side column. contains the logo and sidebar -->
        @include('layouts.partials.sidebar')

        <div class="content-wrapper">
            <div class="container-full">
                <div class="content-header">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <h4 class="page-title">@yield('page_title', 'Page title')</h4>
                            <div class="d-inline-block align-items-center">
                                <nav>
                                    @yield('breadcrumb')
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Main content -->
                @yield('content')
            </div>
        </div>
        <!-- Footer -->
        @include('layouts.partials.footer')
    </div>
    <!-- Vendor JS -->
    <script src="{{ asset('hyper/template/vertical/src/js/vendors.min.js') }}">
    </script>
    <script src="{{ asset('hyper/template/vertical/src/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('hyper/assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('hyper/assets/vendor_components/datatable/datatables.min.js') }}"></script>
    <script src="{{ asset('hyper/assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('hyper/assets/vendor_components/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>

    <script src="{{ asset('hyper/template/vertical/src/js/demo.js') }}"></script>
    <script src="{{ asset('hyper/template/vertical/src/js/template.js') }}"></script>

    <script src="{{ asset('hyper/template/vertical/src/js/pages/data-table.js') }}"></script>
    @stack('scripts')

</body>

</html>