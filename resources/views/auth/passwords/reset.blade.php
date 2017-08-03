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
        <div class="alert">
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

    <form method="POST" action="{{ url('/password/reset') }}">
        {{ csrf_field() }}

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <input type="email" name="email" placeholder="E-Mail Address" value="{{ $email or old('email') }}">
            <span class="loginemailicon"></span>
        </div>

        <div>
            <input type="password" name="password" placeholder="Password">
            <span class="loginemailicon"></span>
        </div>
        <div>
            <input id="password-confirm" type="password" name="password_confirmation" placeholder="Password">
            <span class="loginemailicon"></span>
        </div>

        <button type="submit">RESET</button>
    </form>

@endsection
