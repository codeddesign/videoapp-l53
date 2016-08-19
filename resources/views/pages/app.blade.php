<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='shortcut icon' href='/home/images/logo.png'>
    <title>Video App</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{--include typekit font--}}
    <script src="https://use.typekit.net/lwk5wec.js"></script>
    <script>
        try {
            Typekit.load({
                async: true
            });
        } catch (e) {}
    </script>
</head>

<body>
    <div id="app">
        <app></app>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
