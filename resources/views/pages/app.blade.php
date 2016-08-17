<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta charset="utf-8" id="token" value="{{ csrf_token() }}">
    <link rel='shortcut icon' href='/home/images/logo.png'>
    <title>Video App</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- include typekit font -->
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
    <script type="text/javascript">
//        $(document).ready(function() {
//            // WEBSITE LIST DROPDOWN
//            $('#availablesites, #availablesites-navdroparea').mouseover(function() {
//                $('#availablesites-navdroparea').show();
//                $('#availablesites').css('background', '#303749');
//                //$('#availablesites').css('border-right', '1px solid #303749');
//            });
//
//            $('#availablesites, #availablesites-navdroparea').mouseout(function() {
//                $('#availablesites-navdroparea').hide();
//                $('#availablesites').css('background', 'transparent');
//                //$('#availablesites').css('border-right', '1px solid #5C5882');
//            });
//
//            // ACCOUNT DETAILS DROPDOWN
//            $('#accountdetails, #accountdetails-navdroparea').mouseover(function() {
//                $('#accountdetails-navdroparea').show();
//                $('#accountdetails').css('background', '#303749');
//                $('#accountdetails').css('border-left', '1px solid #303749');
//            });
//
//            $('#accountdetails, #accountdetails-navdroparea').mouseout(function() {
//                $('#accountdetails-navdroparea').hide();
//                $('#accountdetails').css('background', 'transparent');
//                $('#accountdetails').css('border-left', '1px solid #5C5882');
//            });
//        });
    </script>
</body>

</html>
