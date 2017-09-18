<div class="home-getstartformwrap">
    <form class="contactform" method="POST" action="/contact">
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle forname">YOUR NAME</div>
            <input name="name" value="{{old('name')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle foremail">EMAIL ADDRESS</div>
            <input name="email" value="{{old('email')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle forphone">TELEPHONE #</div>
            <input name="phone" value="{{old('phone')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle forcompany">COMPANY</div>
            <input name="company" value="{{old('company')}}">
        </div>

        @if ($mode == 'inquire')
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle forwebsite">WEBSITE URL</div>
            <input name="website" value="{{old('website')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle forpageviews">MONTHLY PAGEVIEWS</div>
            <input name="pageviews" value="{{old('pageviews')}}">
        </div>
        @endif

        @if ($mode == 'contact')
        <div class="home-getstartinputwrap contact-forminquiretextarea">
            <div class="home-getstartinputtitle formessage">MESSAGE</div>
            <textarea name="message"></textarea>
        </div>
        @endif

        {{ csrf_field() }}

        <button type="submit">SUBMIT</button>
    </form>
</div>

@section('body-scripts')
    <script>
        $('.contactform').on('submit', function(ev) {
            ev.preventDefault();

            var $form = $(this);

            $('.home-getstartinputtitle').css('color', '');

            $.ajax({
                url: $form.attr('action'),
                data: $form.serialize(),
                method: 'post',
                success: function() {
                    alert('Your message has been sent. Thank you!');

                    $form[0].reset();
                },
                error: function(result) {
                    Object.keys(result.responseJSON).forEach(function(key) {
                        $('.for'+key+'').css('color', 'red');
                    });
                }
            });
        });
    </script>
@endsection