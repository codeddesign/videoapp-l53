@extends('home.layout')

@section('title')
    About Ad&sup3; Video Ad Tech: Who We Are
@endsection

@section('body')
<div class="company-mainbg">
    @include('home.partial.nav_desktop')

    <div class="company-horline30"></div>
    <div class="company-horline70"></div>
    <div class="company-vertline170"></div>
    <div class="company-vertline200"></div>
    <div class="company-vertline640"></div>
    <div class="company-vertline670"></div>
    <div class="company-mainwrapper">
        <div class="company-mainmargin">
            <div class="company-historytitle">HISTORY</div>
            <div class="company-maindesc">
                AD³ is an advertising technology company that provides publishers with a next generation solution to maximize ad revenue without sacrificing user experience.
                <p>
                    Our patent pending CacheQ process has made AD³ a leader in the video outstream market with fill rates regularly averaging 250%+ on desktop and 75% on mobile. AD³ has partnered with major DSPs and Trading Desks to bring premium brand advertising to our publisher partners. In addition to built-in demand, AD³ provides its own custom video ad server, mediation layer, video player, and realtime analytics (updated in milliseconds). Use our demand partners or bring your own!
                    <p>
                        The AD³ management team comes from the publishing world and has seen first-hand the challenges of today’s video landscape. Advertisers are being confronted with ad fraud unlike anything we’ve experienced before, and ad tech companies specializing in video monetization simply aren’t delivering for their publishers. Meanwhile, the users have to suffer through poor on-page experiences. AD³ technology was originally developed for our own sites to address the problems we faced. We soon realized we could solve the greater market challenges and have been passionate about tackling them ever since.
            </div>
            <div class="company-execteamwrap">
                <div class="company-historytitle">EXECUTIVE TEAM</div>
                <ul>
                    <li>
                        <img src="/home/images/companydanielsnapshot.jpg">
                        <div class="company-execname">Daniel Gouldman</div>
                        <div class="company-exectitle">Chief Executive Officer</div>
                    </li>
                    <li>
                        <img src="/home/images/companybryantsnapshot.png">
                        <div class="company-execname">Bryant Maroney</div>
                        <div class="company-exectitle">Chief Technology Officer</div>
                    </li>
                    <li>
                        <img src="/home/images/companyiansnapshot.jpg">
                        <div class="company-execname">Ian Kane</div>
                        <div class="company-exectitle">Chief Revenue Officer</div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- end .company-mainmargin -->
    </div>
</div>
<!-- end .company-mainbg -->

@include('home.partial.footerblock')
