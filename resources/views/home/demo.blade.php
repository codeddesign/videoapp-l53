@extends('home.layout')

@section('body')
    <!-- @TODO -->

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
                    @if ($info['campaign'] == 1 || $info['campaign'] == 3)
                    <script src="{{ $app->environment('APP_URL') }}/demod/i{{ $info['campaign'] }}.js"></script>
                    @endif
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
                    @if ($info['campaign'] == 2)
                        <script src="{{ $app->environment('APP_URL') }}/demod/i2.js"></script>
                    @endif
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

    <!-- @TODO -->

@endsection