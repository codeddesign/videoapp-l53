@extends('home.layout')

@section('title')
    Ad&sup3; Video Ad Tech | Contact
@endsection

@section('body')
<div class="company-mainbg">
    @include('home.partial.nav_desktop')

    <div class="contact-horlinetop"></div>
    <div class="contact-horlinebottom"></div>
    <div class="contact-atlantaarea">
        <div class="contact-atltextwrap">
            <div class="contact-atltextcity">ATLANTA</div>
            <div class="contact-atltextaddress">Headquarters
                <br>1011 Marble Mill Rd.
                <br>Marietta, GA</div>
            <div class="contact-atltextphone">(470) 419.7743</div>
        </div>
        <div class="contact-atlbg"></div>
    </div>
    <div class="contact-newyorkarea">
        <div class="contact-atltextwrap">
            <div class="contact-atltextcity">NEW YORK</div>
            <div class="contact-atltextaddress">Sales
                <br>sales@ad3media.com</div>
            <div class="contact-atltextphone"></div>
        </div>
        <div class="contact-nybg"></div>
    </div>
    <div class="contact-maintitlewrap">
        <div class="contact-maintitle">CONTACT US</div>
        <div class="contact-maindesc">Have questions?
            <br>Contact our passionate
            <br>and dedicated team
            <br>today!</div>
    </div>
    <div class="contact-inquirewrap">
        <div class="contact-inquireinner">
            <div class="contact-geninquiretitle">GENERAL INQUIRIES</div>

            @include('home.partial.contactform', ['mode' => 'contact'])
        </div>
    </div>

    @include('home.partial.footer')
</div>
@endsection
