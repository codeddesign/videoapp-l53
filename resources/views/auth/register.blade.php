@extends('auth.layout')

@section('content')
    <div class="user-creation">
        <div class="loginform-registertitle">NEW ACCOUNT</div>
        <div class="loginform-error"></div>

        <form action="/register" method="post">
            {{ csrf_field() }}
            @if (count($errors) > 0)
                <div class="error">
                    <ul style="list-style: none; padding-left:20px">
                        @foreach ($errors->all() as $error)
                            <li style="float: left">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <input type="text" name="first_name" placeholder="First Name" required>
                <span class="loginemailicon"></span>
            </div>
            <div>
                <input type="text" name="last_name" placeholder="Last Name" required>
                <span class="loginemailicon"></span>
            </div>
            <div>
                <input type="text" name="company" placeholder="Company" required>
                <span class="loginemailicon"></span>
            </div>
            <div>
                <input type="email" name="email" placeholder="Email" required>
                <span class="loginemailicon"></span>
            </div>

            <div>
                <input type="password" name="password" placeholder="Password" required>
                <span class="loginpassicon"></span>
            </div>

            <div>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <span class="loginpassicon"></span>
            </div>

            <button id="register" type="submit">CONTINUE</button>
        </form>
    </div>

    <div class="loginadditional">
        <div class="login-registerbutton"><a href="/login">LOGIN</a></div>
        <div class="login-lostpassbutton"><a href="/password/reset">LOST PASSWORD</a></div>
    </div>

@endsection
