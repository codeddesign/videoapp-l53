@extends('home.layout')

@section('title')
    Ad&sup3; Video Ad Tech: Video Player Demo
@endsection

@section('body')
<div class="company-mainbg" style="overflow:inherit !important;float:left;">
    @include('home.partial.nav_desktop')

    <div class="demopage-horline30"></div>
    <div class="demopage-horline70"></div>
    <div class="demopage-horline90"></div>
    <div class="company-vertline170"></div>
    <div class="company-vertline200"></div>
    <div class="company-vertline640"></div>
    <div class="company-vertline670"></div>
    <div class="demopage-mainwrapper">
        <div class="demopage-historytitle">VIDEO PLAYER DEMO</div>
        <div class="demopage-contentareawrap">
	        <div class="demopage-maindesc">
	            Starboard bilge ho crack Jennys tea cup fore Pieces of Eight brig shrouds lad bucko. Shrouds chase schooner lass deadlights tender ballast hail-shot doubloon cog. Bounty mizzen broadside shrouds Plate Fleet schooner Sea Legs lee belay Chain Shot.
	            <p>
Long boat sheet aft rope's end trysail shrouds broadside overhaul draught crack Jennys tea cup. Pillage lad grapple chantey Nelsons folly scourge of the seven seas belay matey brig square-rigged. Ahoy maroon main sheet scurvy poop deck splice the main brace weigh anchor mizzen square-rigged fire in the hole.
				<p>
Rigging Gold Road jack marooned coffer quarter ye yard execution dock poop deck. Grog blossom red ensign scuttle gibbet spanker Yellow Jack hearties chase guns Sink me list. Pressgang yawl crow's nest Jack Ketch nipper doubloon aye hands Cat o'nine tails Buccaneer.
				<!-- start video area -->
				@if ($info['campaign'] == 1 || $info['campaign'] == 3)
                    <script src="{{ $app->environment('APP_URL') }}/demod/i{{ $info['campaign'] }}.js"></script>
                @endif
                <!-- end video area -->
				<p>
Bilged on her anchor careen Nelsons folly to go on account fire in the hole measured fer yer chains tackle bucko rigging hang the jib. Grog blossom barkadeer lad scuttle sheet Privateer scuppers keelhaul plunder cutlass. Lass Spanish Main interloper loaded to the gunwalls black jack pink ahoy Brethren of the Coast parley heave down.
				<p>
Doubloon fluke Admiral of the Black bilge coxswain rum crimp shrouds gangway hail-shot. Pillage dead men tell no tales man-of-war landlubber or just lubber ho broadside me bilged on her anchor to go on account crack Jennys tea cup. Long boat rope's end smartly piracy pirate pinnace bilge water spirits Pieces of Eight fluke.
				</p>
Starboard bilge ho crack Jennys tea cup fore Pieces of Eight brig shrouds lad bucko. Shrouds chase schooner lass deadlights tender ballast hail-shot doubloon cog. Bounty mizzen broadside shrouds Plate Fleet schooner Sea Legs lee belay Chain Shot.
	            <p>
Long boat sheet aft rope's end trysail shrouds broadside overhaul draught crack Jennys tea cup. Pillage lad grapple chantey Nelsons folly scourge of the seven seas belay matey brig square-rigged. Ahoy maroon main sheet scurvy poop deck splice the main brace weigh anchor mizzen square-rigged fire in the hole.				
	        </div><!-- end .demopage-maindesc -->
	        <div class="demopage-sidebararea">
		        <!-- start sidebar video -->
		        @if ($info['campaign'] == 2)
                    <script src="{{ $app->environment('APP_URL') }}/demod/i2.js"></script>
                @endif
		        <!-- end sidebar video -->
		        <div class="demopage-sideblock110"></div>
		        <div class="demopage-sideblock270"></div>
		        <div class="demopage-sideblock160"></div>
		        <div class="demopage-sideblock270"></div>
	        </div>
	    </div><!-- end .demopage-contentareawrap -->    
    </div>
</div>
<!-- end .company-mainbg -->

@include('home.partial.footerblock')
























    <!--

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
-->