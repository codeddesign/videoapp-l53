@extends('auth.layout')

@section('content')

    <form action="/login" method="post">
        {{ csrf_field() }}
        @if (count($errors) > 0)
            <div class="error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        @if (session('message'))
            <div class="success">
                {{ session('message') }}
            </div>
        @endif

        @if (session('status'))
            <div class="success">
                {{ session('status') }}
            </div>
        @endif

        <div>
            <input type="email" name="email" placeholder="Email" value="{{ old('email')  }}" required>
            <span class="loginemailicon"></span>
        </div>

        <div>
            <input type="password" name="password" placeholder="Password" required>
            <span class="loginpassicon"></span>
        </div>

        <button id="login" type="submit">LOGIN</button>
    </form>
    <div class="loginadditional">
        <!--<div class="login-registerbutton"><a href="/register">REGISTER</a></div>-->
        <div class="login-lostpassbutton"><a href="/password/reset">LOST PASSWORD</a></div>
    </div>
@endsection
