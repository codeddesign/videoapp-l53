<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>
<h2>Your new account</h2>

<div>
    <p>Hello, {{ $user->first_name }}. You can login using the following credentials:</p>
    <p>Email: {{ $user->email }}</p>
    <p>Password: {{ $password }}</p>

    <p>
        <a href="{{ URL::to('login/') }}">
            {{ URL::to('login') }}
        </a>
    </p>

    <p>For security reasons, please set a new password as soon as possible.</p>
</div>

</body>

</html>
