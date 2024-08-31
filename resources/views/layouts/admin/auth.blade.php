<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Google Fonts -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js"></script> -->
        <script>
          WebFont.load({
            google: {"families":["Montserrat:400,500,600,700","Noto+Sans:400,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
        </script>
        <!-- Favicon -->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admin/img/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('admin/img/favicon-32x32.png')}}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/img/favicon-16x16.png')}}">
        <!-- Stylesheet -->
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/base/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset('admin/vendors/css/base/elisyam-1.5.min.css')}}">
        @yield('styles')
    </head>
    <body class="bg-fixed-02">
        <!-- Begin Preloader -->
        <div id="preloader">
            <div class="canvas">
                <img src="{{ asset('admin/img/logo.png')}}" alt="logo" class="loader-logo">
                <div class="spinner"></div>   
            </div>
        </div>
        <!-- End Preloader -->
        <!-- Begin Container -->
        <div class="container-fluid h-100 overflow-y">
            @yield("content")
        </div>  
        <!-- End Container --> 
        <!-- Begin Vendor Js -->
        <script src="{{ asset('admin/vendors/js/base/jquery.min.js')}}"></script>
        <script src="{{ asset('admin/vendors/js/base/core.min.js')}}"></script>
        <!-- End Vendor Js -->
        <!-- Begin Page Vendor Js -->
        <script src="{{ asset('admin/vendors/js/app/app.min.js')}}"></script>
        <!-- End Page Vendor Js -->
        @yield('scripts')
    </body>
</html>

