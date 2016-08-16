@extends('auth.layout')

@section('content')
    <h1>{{ auth()->user()->email }}</h1>
    <div class="user-creation">
        <div class="loginform-registertitle">NEW ACCOUNT</div>
        <div class="loginform-error"></div>

        <form action="#">
            {{ csrf_field() }}
            <div class="error">@{{ error }}</div>

            <div>
                <input type="tel" name="phone" placeholder="Phone number.." required>
                <span class="loginemailicon"></span>
            </div>

            <button>VERIFY NUMBER</button>
        </form>

        <form action="#">
            <div class="error">@{{ error }}</div>

            <div>
                <input type="text" name="phone_code" placeholder="Enter verification number.." required>
            </div>

            <button>CONFIRM VERIFICATION NUMBER</button>
        </form>

        <div v-show="step == 'completed'" style="text-align: center;color: white;">
            <img src="/assets/images/verify-success.png">

            <p>Success!</p>
            <p>Proceed to login page</p>
        </div>
    </div>
@endsection
