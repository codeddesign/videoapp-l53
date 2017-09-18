<!DOCTYPE html>
<html>

<head>
    <title>@yield('title')</title>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="viewport" content="width=550, user-scalable=no">
    <meta name="description" content="Ad3 Media delivers the highest converting advertising solutions the market has to offer. An entire system built on simplicity with you in mind.">

    <!-- FACEBOOK OG -->
    <meta property="fb:app_id" content="344924649174257" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Ad&sup3; Media | High Impact Ad Solutions" />
    <meta property="og:description" content="Ad3 delivers the highest converting advertising solutions with the highest CPM's the market has to offer."/>
    <meta property="og:url" content="http://ad3media.com" />
    <meta property="og:site_name" content="Ad3 Media" />
    <meta property="og:image" content="" />
    <meta property="og:locale" content="en_US" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:card" content="summary_large_image" />
    <!-- END FACEBOOK OG -->

    <link rel="shortcut icon" href="/home/images/ad3favicon.png">
    <link href="/home/style.css" rel="stylesheet" type="text/css">
    <link href="/home/responsive.css" rel="stylesheet" type="text/css">

    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="https://use.typekit.net/lwk5wec.js"></script>
    <script>try {Typekit.load({ async: true });} catch (e) {};</script>
</head>
<body>
    @include('home.partial.nav_mobile')

    @yield('body')

    <script>
    // MOBILE NAVIGATION CHANGE
    $(document).ready(function() {
        // SHOW/HIDE NAV OVERLAY
        $('.mobilehamburg').click(function() {
            $('.mainmobilenavoverlay').show();
            $(this).css('display', 'none');
            $('.closemobilehamburg').css('display', 'block');
            $('body').css('overflow', 'hidden');
        });
        $('.closemobilehamburg').click(function() {
            $('.mainmobilenavoverlay').hide();
            $(this).css('display', 'none');
            $('.mobilehamburg').css('display', 'block');
            $('body').css('overflow', 'inherit');
        });
    });
    </script>
    @yield('body-scripts')
</body>
</html>
