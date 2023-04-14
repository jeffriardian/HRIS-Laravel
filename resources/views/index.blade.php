<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1.0">

    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#001a3d">
    <meta name="msapplication-TileColor" content="#001a3d">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <noscript>
        <strong>We're sorry but application doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <div id="app"></div>

    <script>
    window.laravel = {
        echo:{
            port: {!! json_encode(env('LARAVEL_ECHO_SERVER_PORT')) !!}
        }
    };
    </script>

    {{-- <script src="//{{ Request::getHost() }}:{{env('LARAVEL_ECHO_SERVER_PORT')}}/socket.io/socket.io.js"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
