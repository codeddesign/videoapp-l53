<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='shortcut icon' href='/home/images/logo.png'>
    <title>Video App</title>
</head>

<body>
    <div id="app">
        <app></app>
    </div>
    <script src="http://192.168.10.10:6001/socket.io/socket.io.js"></script>
    @if($webpack)
        <script src="http://192.168.10.10:8080/js/app.js"></script>
    @else
        <script src="{{ asset('js/app.js') }}"></script>
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
</body>

</html>
