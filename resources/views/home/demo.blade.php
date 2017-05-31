<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="viewport" content="width=550, user-scalable=no">
    <title>Ad&sup3; Media | {{ $info['title'] }} Video Demo</title>
    <meta name="description" content="Ad3 Media delivers the highest converting advertising solutions the market has to offer. An entire system built on simplicity with you in mind.">
    <link rel='shortcut icon' href='/home/images/ad3favicon.png'>
    <link href="../home/style.css?v=2" rel="stylesheet" type="text/css">
	<link href="../home/responsive.css" rel="stylesheet" type="text/css">

    <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>

    <!-- GOOGLE FONT TYPE -->
    <link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>

    <!-- TYPEKIT FONT TYPE -->
    <script src="https://use.typekit.net/lwk5wec.js"></script>
    <script>
    try {
        Typekit.load({
            async: true
        });
    } catch (e) {}
    </script>

    <!-- FACEBOOK OG -->
    <meta property="fb:app_id" content="344924649174257" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Ad&sup3; Media | High Impact Ad Solutions" />
    <meta property="og:description" content="Ad3 delivers the highest converting advertising solutions with the highest CPM's the market has to offer." />
    <meta property="og:url" content="http://ad3media.com" />
    <meta property="og:site_name" content="Ad3 Media" />
    <meta property="og:image" content="" />
    <meta property="og:locale" content="en_US" />
    <meta name="twitter:image" content="" />
    <meta name="twitter:card" content="summary_large_image" />
    <!-- END FACEBOOK OG -->

</head>

<body>
    <div class="mainnav-overlay">
        <ul class="navoverlay-list">
            <li>WP PLUGIN</li>
            <li>DEVELOPER API</li>
            <li>ABOUT US</li>
            <li>GET STARTED WITH AD3</li>
        </ul>
    </div>

    <div class="mainlogowrapper">
        <div class="navwrap">
            <a href="/">
                <div class="logo"></div>
            </a>
            <ul class="headernav">
                <a href="/features">
                    <li>PUBLISHERS</li>
                </a>
                <a href="/features">
                    <li>FEATURES</li>
                </a>
                <a href="/contact">
                    <li>CONTACT</li>
                </a>
                <li>
                    <div class="navmore"></div>
                    <div class="closenavmore"></div>
                </li>
            </ul>
        </div>
    </div>

    <div class="demopage-headerdark"></div>

    <div class="demopage-mainwrapper">
        <div class="demopage-maintitlewrap">
            <div class="demopage-maintitlewrapper">
            	<div class="demopage-titleblueback"></div>
				<div class="demopage-maintitle">{{ $info['title'] }} Video Demo</div>
            </div>
            	
            <div class="demopage-scrollareawrap">
                <div class="demopage-scrollleft" style="margin-top: -9px;">
                    <div class="demopage-scrolldowntext"><span></span> Scroll down to view ad <span></span></div>
                    <div class="demopage-largearea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <center>
                    @if ($info['campaign'] == 1 || $info['campaign'] == 3)
                    <script src="{{ $app->environment('APP_URL') }}/demod/i{{ $info['campaign'] }}.js"></script>
                    @endif
                    </center>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                </div>
                <div class="demopage-scrollright">
	                <center>
                    @if ($info['campaign'] == 2)
                        <script src="{{ $app->environment('APP_URL') }}/demod/i2.js"></script>
                    @endif
	                </center>
                    <div class="demopage-largemidarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallmidarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-midmidarea"></div>
                    <div class="demopage-xlargemidarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallarea"></div>
                    <div class="demopage-smallmidarea"></div>
                    <div class="demopage-smallarea"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="demopage-placeoptions">
        <div class="demopage-placementtitle">Ad Placement Options</div>
        <div class="demopage-placementdesc">Test out all of our ad placement options:</div>
        <div class="demopage-placewrap">
            <ul class="demopage-placers">
                <li>
                    <div class="demopage-placeimage"></div>
                    <div class="demopage-placetitle">In-Article Video</div>
                    <div class="demopage-placesub">
                        <a href="/demo/in-article">view demo</a>
                    </div>
                </li>
                <li>
                    <div class="demopage-placeimage"></div>
                    <div class="demopage-placetitle">Sidebar Video</div>
                    <div class="demopage-placesub">
                        <a href="/demo/sidebar">view demo</a>
                    </div>
                </li>
                <li>
                    <div class="demopage-placeimage"></div>
                    <div class="demopage-placetitle">Display Plus</div>
                    <div class="demopage-placesub">
                        <a href="/demo/display-plus">view demo</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="homepage-advertiserswrap">
        <div class="homeadvertisers-title">Access to 1,000’s of Premium Advertisers</div>
        <ul class="homeadvertisers-list">
            <li>Coca-Cola</li>
            <li>Sony</li>
            <li>Microsoft</li>
            <li>Disney</li>
            <li>Verizon</li>
            <li>Apple</li>
        </ul>
    </div>

    @include('home.partial.betasignup')

    @include('home.footer')
