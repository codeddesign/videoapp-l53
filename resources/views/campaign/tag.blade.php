<?xml version="1.0" encoding="UTF-8"?>
<VAST version="2.0">
@foreach($tags as $tagIndex => $tag)
    <Ad id="a{{$tagIndex}}">
        <Wrapper>
            <AdSystem version="1.0">ad3media.com</AdSystem>
            <VASTAdTagURI><![CDATA[{{$tag->url}}]]></VASTAdTagURI>
            <Error><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=[error_code]&_rd={{time()}}]]></Error>
            <Impression><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=3&_rd={{time()}}]]></Impression>
            <Creatives id="a{{$tagIndex}}">
                <Creative>
                    <Linear>
                        <TrackingEvents>
                            <Tracking event="start"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=2&_rd={{time()}}]]></Tracking>
                            <Tracking event="firstQuartile"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=4&_rd={{time()}}]]></Tracking>
                            <Tracking event="midpoint"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=5&_rd={{time()}}]]></Tracking>
                            <Tracking event="thirdQuartile"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=6&_rd={{time()}}]]></Tracking>
                            <Tracking event="complete"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=7&_rd={{time()}}]]></Tracking>
                            <Tracking event="skip"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=8&_rd={{time()}}]]></Tracking>
                            <Tracking event="pause"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=9&_rd={{time()}}]]></Tracking>
                            <Tracking event="resume"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=10&_rd={{time()}}]]></Tracking>
                            <Tracking event="mute"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=11&_rd={{time()}}]]></Tracking>
                            <Tracking event="unmute"><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=12&_rd={{time()}}]]></Tracking>
                        </TrackingEvents>
                        <VideoClicks>
                            <ClickThrough><![CDATA[{{env('APP_TRACK')}}/track?w=0&campaign={{$campaign['id']}}&platform={{$platform}}&tag={{$tag->id}}&source=ad&status=14&_rd={{time()}}]]></ClickThrough>
                        </VideoClicks>
                    </Linear>
                </Creative>
            </Creatives>
        </Wrapper>
    </Ad>
@endforeach
</VAST>