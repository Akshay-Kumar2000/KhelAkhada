<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="manifest" href="/manifest.json">
    <link rel="icon" href="{{ asset('front/images/khelmoj123.jpeg') }}" />
    <title>akplayers| Khelo Dil se, Jeeto Dimag se</title>
    <meta content="KhelBro" name="description">
    <meta name="robots" content="noindex">
    <meta
        content="ludo khelo,online ludo, online games, play with real players, best ludo website, ludo earning, earn by playing ludo, playing ludo king,  ludo contest, Best Ludo website in kota , ludo tournament , ludo khelo paise kamao, khelo ludo, Ludo Players, Ludo king."
        name="keywords">

    @yield('cache_meta')

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,500,600,700,800,900">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('front/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('front/css/responsive.css') }}">

    @yield('styles')
    <script src="{{ asset('front/js/jquery-3.6.1.min.js') }}"></script>


</head>

<body>

    @include('partials.front.navigation')
    <div class="leftContainer">
        @include('partials.front.header')
        @yield('content')
        @include('partials.front.footer')
    </div>

</body>

</html>
