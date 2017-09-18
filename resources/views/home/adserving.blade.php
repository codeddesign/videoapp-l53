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
                        <div class="home-aboutseclisttitle">Manage Demand</div>
                        <div class="home-aboutseclistdesc">19kb in size, and loading in less than 100ms.</div>
                    </div>
                </p>
            </li>
            <li>
                <p>
                    <div class="home-aboutseclist">
                        <div class="home-aboutseclistnum">04</div>
                        <div class="home-aboutseclisttitle">Free IVT Reporting</div>
                        <div class="home-aboutseclistdesc">19kb in size, and loading in less than 100ms.</div>
                    </div>
                </p>
            </li>
        </ul>
        <div class="home-aboutprebuttonwrap adserv-aboutprebuttonwrap">
            <div class="home-aboutfirstprebutton">
                <p>Curious? Check out our Demo to see it in action</p>
            </div>
            <div class="home-aboutprebutton">View Demo</div>
        </div>
    </div>
</div>
<!-- end .cacheq-topaboutwrap -->

@include('home.partial.footerblock')
