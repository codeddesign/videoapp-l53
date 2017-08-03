@extends('auth.layout')

@section('content')

    <style>
        .alert {
            text-align: center;
            padding-bottom: 20px;
            color: #FFF;
        }
    </style>

    @if (count($errors) > 0)
        <div class="error">
            <ul style="list-style: none; padding-left:20px">
                @foreach ($errors->all() as $error)
                    <li style="float: left">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div class="alert">
            {{ session('status') }}
        </div>
    @endif

    <form action="/password/email" method="post">
        {{ csrf_field() }}
        <div>
            <input type="email" name="email" placeholder="E-Mail Address" required>
            <span class="loginemailicon"></span>
        </div>

        <button type="submit">SEND</button>
    </form>

    <div class="loginadditional">
        <div class="login-lostpassbutton"><a href="/login">LOGIN</a></div>
    </div>
@endsection
