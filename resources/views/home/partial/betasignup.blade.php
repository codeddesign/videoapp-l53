<div id="betasignup" class="homepage-startearning">
    <div class="homepage-earningwrap">
        <div class="homepage-earningtitle">Start Earning More Today</div>
        <div class="homepage-earningdesc">Proven conversion rates, industry leading revenue returns, and powerful reporting.
            <br> Talk with our Support Team Today to Get Started!
        </div>

        @if (session('status'))
        <div style="color:#4596CB;font-size:18px;line-height:31px;text-align:center;margin-bottom:50px;">
            Thank you signing up for Ad3 Beta.
            <br> A member of our support team will be in contact with you shortly.
        </div>
        @else
        <form action="/signup" id="signup-form" class="startearningmore validate" method="POST">
            <input name="name" id="mce-FNAME" placeholder="full name">
            <input name="email" id="mce-EMAIL" placeholder="email address">
            <input name="website" id="mce-WEBSITE" placeholder="website">
            <input name="phone" id="mce-PHONE" placeholder="phone number">

            <div id="mce-responses" class="clear">
                <div class="response" id="mce-error-response" style="display:none"></div>
                <div class="response" id="mce-success-response" style="display:none"></div>
            </div>

            {{ csrf_field() }}
            <input type="submit" value="Get Started" name="subscribe" id="mc-embedded-subscribe" class="button">
        </form>
        @endif
    </div>
</div>
