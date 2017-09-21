@extends('home.layout')

@section('title')
    Ad&sup3; Ad Tech | Premium Video Ad Server
@endsection

@section('body')
<div class="adserv-mainbg">
    @include('home.partial.nav_desktop')

    <div class="adserv-topline20"></div>
    <div class="adserv-topline25"></div>
    <div class="adserv-horline30"></div>
    <div class="adserv-maintitlearea">
        <div class="adserv-maintitlesub">REAL-TIME DATA AT YOUR FINGERTIPS</div>
        <div class="adserv-maintitle">MAKING DASHBOARDS GREAT AGAIN</div>
    </div>
    <div class="adserv-headbgwrap">
        <div class="adserv-headbg"></div>
    </div>
    <div class="adserv-headerdesc">Monitor real-time statistics at both macro and micro levels with full Invalid-Traffic, Ad Block, and Bounce rate reporting to provide you with the most accurate monetization tracking system available today.</div>
    <div class="adserv-aboutseclist">
        <ul class="home-aboutseclistwrap">
            <li>
                <p>
                    <div class="home-aboutseclist">
                        <div class="home-aboutseclistnum">01</div>
                        <div class="home-aboutseclisttitle">Real-time Stats</div>
                        <div class="home-aboutseclistdesc">All VAST/VPAID versions supported, including Flash.</div>
                    </div>
                </p>
            </li>
            <li>
                <p>
                    <div class="home-aboutseclist">
                        <div class="home-aboutseclistnum">02</div>
                        <div class="home-aboutseclisttitle">Campaign Management</div>
                        <div class="home-aboutseclistdesc">Complete server-side ad request’s.</div>
                    </div>
                </p>
            </li>
            <li>
                <p>
                    <div class="home-aboutseclist">
                        <div class="home-aboutseclistnum">03</div>
                        <div class="home-aboutseclisttitle">Demand Management</div>
                        <div class="home-aboutseclistdesc">Utilize our pre-setup demand partners, or use your own.</div>
                    </div>
                </p>
            </li>
            <li>
                <p>
                    <div class="home-aboutseclist">
                        <div class="home-aboutseclistnum">04</div>
                        <div class="home-aboutseclisttitle">Free Fraud Prevention</div>
                        <div class="home-aboutseclistdesc">Built-in bot & fraud management to keep fill high.</div>
                    </div>
                </p>
            </li>
        </ul>
        <div class="home-aboutprebuttonwrap adserv-aboutprebuttonwrap">
            <div class="home-aboutfirstprebutton">
                <p>Curious? Check out our Demo to see it in action</p>
            </div>
            <a href="/demo/display-plus"><div class="home-aboutprebutton">View Demo</div></a>
        </div>
    </div>
</div>
<!-- end .cacheq-topaboutwrap -->

@include('home.partial.footerblock')
