<!DOCTYPE html>
<html>
<head>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8"/>
        <meta charset="utf-8" id="token" value="{{ csrf_token() }}">
        <title>Ad³ Ad Server</title>
        <link href="{{asset('css/auth.css')}}" rel="stylesheet" type="text/css">
    </head>
</head>

<body id="loginpage">
    <div class="loginwrapper">
        <div class="loginlogo">
            <center>
                <a href="{{ route('home') }}">
                    <img src="/images/videologo.png" width="78" height="29">
                </a>
            </center>
        </div>

        @yield('content')
    </div>

    @yield('scripts')
</body>

</html>
