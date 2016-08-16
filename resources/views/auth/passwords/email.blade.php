@extends('auth.layout')

@section('content')
    <form action="/password/reset" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div>
            <input type="email" name="username" placeholder="email address.." required>
            <span class="loginemailicon"></span>
        </div>

        <button>SEND</button>
    </form>

    @include('auth.partials.options', ['on' => 'recover'])

@endsection
