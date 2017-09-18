<div class="home-getstartformwrap">
    <form method="POST" action="/contact">
        @if (count($errors->all()))
        <div class="errors">
            @foreach($errors->all() as $message)
                <div>{{ $message }}</div>
            @endforeach
        </div>
        @endif

        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">YOUR NAME</div>
            <input name="name" value="{{old('name')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">EMAIL ADDRESS</div>
            <input name="email" value="{{old('email')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">TELEPHONE #</div>
            <input name="phone" value="{{old('phone')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">COMPANY</div>
            <input name="company" value="{{old('company')}}">
        </div>

        @if ($mode == 'inquire')
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">WEBSITE URL</div>
            <input name="website" value="{{old('website')}}">
        </div>
        <div class="home-getstartinputwrap">
            <div class="home-getstartinputtitle">MONTHLY PAGEVIEWS</div>
            <input name="pageviews" value="{{old('pageviews')}}">
        </div>
        @endif

        @if ($mode == 'contact')
        <div class="home-getstartinputwrap contact-forminquiretextarea">
            <div class="home-getstartinputtitle">MESSAGE</div>
            <textarea name="message"></textarea>
        </div>
        @endif

        {{ csrf_field() }}

        <button type="submit">SUBMIT</button>
    </form>
</div>