@extends('home.layout')

@section('title')
    Ad&sup3; CacheQ | High Revenue Video Ad Technology
@endsection

@section('body')

<div class="cacheq-topaboutwrap">
    @include('home.partial.nav_desktop')

    <div class="cacheq-mainhorline"></div>
    <div class="cacheq-patentleftline"></div>
    <div class="cacheq-patent01">PATENTED TECHNOLOGY</div>
    <div class="cacheq-topblueheader"></div>
    <div class="maincacheqlogo">CacheQ Video Player</div>
    <div class="maincacheqsubtitle">The latest in video ad technology right at your fingertips.</div>
    <!-- MOBILE VIDEO AREA -->
    <div class="cacheq-mobilevidimage">
        <div class="cacheq-mobilevidmockwrap">
            <ul>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
            <div class="cacheq-mobileqtitleft">A QUEUE OF VIDEO AD’S PLAYING SEQUENTIALLY</div>
            <div class="cacheq-mobileqtitright">RESULTING IN DRAMATIC UPLIFT OF FILL AND REVENNUE</div>
        </div>
        <!-- MOBILE CIRCLE AREA -->
        <div class="cacheq-maincirclewrapper">
            <div class="cacheq-mobilepicwrap">
                <div class="cacheq-mobilepic"></div>
            </div>
            <div class="cacheq-maincirclewrap">
                <div class="cacheq-maincirclelarge">
                    <div class="cacheq-maincirclelargepin"></div>
                </div>
                <div class="cacheq-maincirclesmall">
                    <div class="cacheq-maincirclesmallpin"></div>
                </div>
            </div>
        </div>
        <!-- END MOBILE CIRCLE AREA -->
    </div>
    <!-- END MOBILE VIDEO AREA -->
    <div class="cacheq-howitworkstitle">How it works:</div>
    <div class="cacheq-howitworksblock">
        <div class="cacheq-howitworkswrapper">
            <div class="cacheq-howblockleft">
                <div class="cacheq-howblockimagearea">
                    <img src="/home/images/cacheqserverblock.gif">
                </div>
                <div class="cacheq-howblocktitlearea">Asynchronous Requests</div>
                <div class="cacheq-howtoblockdesc">Asynchronous Requests are made to all demand partners from the server-side
                    <br>(compared to in the browser).</div>
            </div>
            <div class="cacheq-howdirectional"></div>
            <div class="cacheq-howblockcenter">
                <div class="cacheq-howblockimagearea">
                    <img src="/home/images/cacheqxtransfer.gif">
                </div>
                <div class="cacheq-howblickareacenter">
                    <div class="cacheq-howblocktitlearea">Advertisers send Demand</div>
                    <div class="cacheq-howtoblockdesc">Demand partners receive request’s, and send back multiple video advertisements to be played.</div>
                </div>
            </div>
            <div class="cacheq-howdirectional"></div>
            <div class="cacheq-howblockright">
                <div class="cacheq-howblockimagearea">
                    <img src="/home/images/cacheqvideosequence.gif">
                </div>
                <div class="cacheq-howblocktitlearea">Demand Placed in Queue</div>
                <div class="cacheq-howtoblockdesc">CacheQ lines up all advertisements based on CPM, and plays each ad
                    <br>back-to-back.</div>
            </div>
        </div>
    </div>
    <!-- end .cacheq-howitworksblock -->
    <div class="cacheq-endresultwrap">
        <div class="cacheq-endresultpoint">
            <p><span>End Result:</span> Up to 400% fill and increased revenue for you.</p>
        </div>
    </div>
    <div class="cacheq-underhowtohorline"></div>
    <div class="cacheq-largecentertextwrap">
        <div class="cacheq-largecentertext">CacheQ is a fully managed video ad technology, allowing you and your team more time to focus on what’s really important - growing your business.</div>
    </div>
    <div class="cacheq-watchvidwrap">
        <div class="cacheq-watchvidborder">
            <div class="cacheq-watchvidplay"></div>
            <div class="cacheq-watchvidtext">Watch how our CacheQ video system works.</div>
        </div>
    </div>
    <div class="cacheq-seclist">
        <ul class="home-aboutseclistwrap cacheq-seclistwrap">
            <li>
                <p>
                </p>
                <div class="home-aboutseclist">
                    <div class="home-aboutseclistnum">01</div>
                    <div class="home-aboutseclisttitle">Server-side Ad Calls</div>
                    <div class="home-aboutseclistdesc">Advertising should never weigh down your site.
                        <br>All ad request’s we make are done on the server-side.</div>
                </div>
                <p></p>
            </li>
            <li>
                <p>
                </p>
                <div class="home-aboutseclist">
                    <div class="home-aboutseclistnum">02</div>
                    <div class="home-aboutseclisttitle">Full VAST/VPAID Support with RTB</div>
                    <div class="home-aboutseclistdesc">All VAST/VPAID versions supported, including Flash.
                        <br>Do you have direct buys? We also support RTB!</div>
                </div>
                <p></p>
            </li>
            <li>
                <p>
                </p>
                <div class="home-aboutseclist">
                    <div class="home-aboutseclistnum">03</div>
                    <div class="home-aboutseclisttitle">Error Filtering</div>
                    <div class="home-aboutseclistdesc">Utilizing a proprietary method, we successful filter out all VPAID and video errors before they reach the user.</div>
                </div>
                <p></p>
            </li>
        </ul>
        <div class="cacheq-seclistcenter">
            <div class="cacheq-notsureyetwrap">
                <div class="cacheq-notsureprebutton">
                    <p>Are you still not sure?</p>
                </div>
                <div class="cacheq-notsurepostbutton">Contact Us</div>
            </div>
        </div>
        <!-- end .cacheq-seclistcenter -->
    </div>
    <!-- end .cacheq-seclist -->
</div>
<!-- end .cacheq-topaboutwrap -->

@include('home.partial.footerblock')