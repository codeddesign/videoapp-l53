@extends('auth.layout')

@section('content')

    @if (! session('verify'))
        <form action="/verify/phone" method="post">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <div>
                <input type="tel" name="phone" placeholder="Phone number..">
                <span class="loginemailicon"></span>
            </div>

            <button>VERIFY NUMBER</button>
        </form>
    @else
        <form action="/verify/phone/code" method="post">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <div>
                <input type="text" name="phone_code" placeholder="Enter verification number.." required v-model="user.phone_code" v-el:phoneCode>
            </div>

            <button>CONFIRM VERIFICATION NUMBER</button>
        </form>
    @endif
@endsection
