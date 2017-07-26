<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="viewport" content="width=550, user-scalable=no">
    <title>Ad&sup3; Media | High Impact Ad Solutions</title>
    <link href="home/style.css?v=4" rel="stylesheet" type="text/css">
	<link href="home/responsive.css" rel="stylesheet" type="text/css">

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
                <a href="/app">
                    <li>LOGIN</li>
                </a>
                <li>
                    <div class="navmore"></div>
                    <div class="closenavmore"></div>
                </li>
            </ul>
        </div>
    </div>

    <div class="contact-headerarea">
        <div class="headercontactmap"></div>
        <div class="headercontact-titlewrap">
            <div class="headercontact-maintitle">GET IN TOUCH</div>
            <div class="headercontact-subtitle">WHEN YOU HAVE QUESTIONS, WE HAVE ANSWERS.</div>
        </div>
    </div>

    <div class="sendmessage-wrapper">
        <div class="sendmessage-wrapbox">
            <div class="sendmessage-leftbox">
                <div class="sendmessage-leftheader">
                    @if (session('status'))
                        Thank you contacting us.
                    @else
                        Send Us a Message
                    @endif
                </div>
                <div class="sendmessage-leftbg" @if (session('status')) style="display: flex; justify-content: center;align-items: center;" @endif>
                    @if (session('status'))
                    <div style="color:#4596CB;font-size:18px;line-height:31px;text-align:center;margin-bottom:50px;">
                        A member of our support team will be in contact with you shortly.
                    </div>
                    @else
                    <form class="sendmessage-contactform" method="post" id="contactform">
                        <input placeholder="Full Name" name="name">
                        <input placeholder="Email Address" name="email">
                        <input placeholder="Phone #" name="phone">
                        <input placeholder="Company" name="company">
                        <textarea placeholder="Your Message" name="message"></textarea>

                        {{ csrf_field() }}
                        <submit>Send Message</submit>
                    </form>
                    @endif
                </div>
            </div>
            <div class="sendmessage-rightbox">
                <div class="sendmessage-rightheader">Contact Information</div>
                <div class="sendmessage-rightbg">
                    <div class="sendmessage-calluswrap">
                        <div class="sendmessage-callustitle">Give us a call</div>
                        <div class="sendmessage-callussub">(470) 419-7743</div>
                    </div>
                    <div class="sendmessage-emailuswrap">
                        <div class="sendmessage-callustitle">Publisher Support</div>
                        <div class="sendmessage-emailussub">support@ad3media.com</div>
                    </div>
                    <div class="sendmessage-emailuswrap">
                        <div class="sendmessage-callustitle">Sales Department</div>
                        <div class="sendmessage-emailussub">sales@ad3media.com</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('form > submit').click(function() {
            $('#contactform').submit();
        });
    </script>

    @include('home.footer')
