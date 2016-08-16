<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>
<h2>Verify Your Email Address</h2>

<div>
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
</div>

</body>

</html>
