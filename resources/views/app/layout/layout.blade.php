<!DOCTYPE html>

{{-- <html class="loading" lang="{{ LaravelLocalization::getCurrentLocale() }}"
    data-textdirection="{{ LaravelLocalization::getCurrentLocaleDirection() }}"> --}}


<html class="loading" ">

<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description"
        content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    @yield('seo-breadcrumb')

    <title>@yield('page-title') - {{ env('APP_NAME') }}</title>
    <link rel="apple-touch-icon" href="{{ asset('app-assets') }}/images/ico/apple-icon-120.html">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('app-assets') }}/images/ico/favicon.ico">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500&display=swap"
        rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/vendors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/extensions/toastr.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/animate/animate.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/extensions/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/vendors/css/forms/select/select2.min.css">
    @yield('page-vendor')

    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/bootstrap-extended.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/colors.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/components.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/themes/dark-layout.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/themes/bordered-layout.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/themes/semi-dark-layout.min.css">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/core/menu/menu-types/vertical-menu.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-sweet-alerts.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/css/plugins/extensions/ext-component-toastr.min.css">
    @yield('page-css')
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets') }}/css/app.min.css">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('app-assets') }}/vendors/css/bootstrap-icons/font/bootstrap-icons.css">
    <!-- END: Custom CSS-->

    @yield('custom-css')
    <script src="{{ asset('app-assets') }}/vendors/js/vendors.min.js"></script>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click"
    data-menu="vertical-menu-modern" data-col="">

    {{ view('app.layout.topbar') }}

    {{ view('app.layout.leftbar') }}

    <!-- BEGIN: Content-->
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">

            {{ view('app.layout.alerts') }}

            @if (!request()->routeIs('dashboard'))
                <div class="content-header row">
                    @yield('breadcrumbs')

                    <div class="content-header-right text-md-end col-md-3 col-12 d-md-block d-none">
                        <div class="mb-1 breadcrumb-right">
                            <div class="dropdown">
                                <button class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i data-feather="grid"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="app-todo.html">
                                        <i class="me-1" data-feather="check-square"></i>
                                        <span class="align-middle">Todo</span>
                                    </a>
                                    <a class="dropdown-item" href="app-chat.html">
                                        <i class="me-1" data-feather="message-square"></i>
                                        <span class="align-middle">Chat</span>
                                    </a>
                                    <a class="dropdown-item" href="app-email.html">
                                        <i class="me-1" data-feather="mail"></i>
                                        <span class="align-middle">Email</span>
                                    </a>
                                    <a class="dropdown-item" href="app-calendar.html">
                                        <i class="me-1" data-feather="calendar"></i>
                                        <span class="align-middle">Calendar</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="content-header row">
            </div>
            <div class="content-body">
                @yield('content')
            </div>
        </div>
    </div>
    <!-- END: Content-->

    {{ view('app.layout.customizer') }}

    <div class="sidenav-overlay"></div>
    <div class="drag-target"></div>

    {{ view('app.layout.footer') }}


    <!-- BEGIN: Page Vendor JS-->
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/toastr.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/sweetalert2.all.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/extensions/polyfill.min.js"></script>
    @yield('vendor-js')
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('app-assets') }}/js/core/app-menu.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/core/app.min.js"></script>
    <script src="{{ asset('app-assets') }}/js/scripts/customizer.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendors/js/forms/select/select2.full.min.js"></script>
    {{-- <script src="{{ asset('app-assets') }}/vendors/datatables/buttons.server-side.js"></script> --}}
    <script src="{{ asset('app-assets') }}/js/scripts/forms/form-select2.min.js"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    @yield('page-js')
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })

        // function refreshCache(element){
        //     $(this).removeClass('spin-hover').addClass('spinner');
        // }
    </script>
    @yield('custom-js')
</body>
<!-- END: Body-->

</html>
