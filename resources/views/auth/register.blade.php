@extends('auth.layout')

@section('content')
    <div class="user-creation">
        <div class="loginform-registertitle">NEW ACCOUNT</div>
        <div class="loginform-error"></div>

        <form action="/register" method="post">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <div>
                <input type="email" name="email" placeholder="Email" required>
                <span class="loginemailicon"></span>
            </div>

            <div>
                <input type="password" name="password" placeholder="Password" required>
                <span class="loginpassicon"></span>
            </div>

            <div>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <span class="loginpassicon"></span>
            </div>

            <button id="login" type="submit">CONTINUE</button>
        </form>
    </div>

    <div class="loginadditional">
        <div class="login-registerbutton"><a href="/login">LOGIN</a></div>
        <div class="login-lostpassbutton"><a href="/password/reset">LOST PASSWORD</a></div>
    </div>

@endsection
