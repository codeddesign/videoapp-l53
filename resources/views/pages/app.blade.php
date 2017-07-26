<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='shortcut icon' href='/home/images/ad3favicon.png'>
    <title>Ad&sup3; Ad Server</title>
</head>

<body>
    <div id="app">
        <app></app>
    </div>
    <script>
        window.socketIoIp = '{{ $socketIoIp }}';
        window.apiDomain = '{{ $apiDomain }}';
    </script>
    <!--<script src="//{{ $socketIoIp }}:3000/socket.io/socket.io.js"></script>-->
    @if($webpack)
        <script src="http://{{ $webpack }}:8080/js/app.js"></script>
    @else
        <script src="{{ asset($jsBundle) }}"></script>
    @endif

    {{--include typekit font--}}
    <script src="https://use.typekit.net/lwk5wec.js"></script>
    <script>
        try {
            Typekit.load({
                async: true
            });
        } catch (e) {}
    </script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>-->
</body>

</html>
