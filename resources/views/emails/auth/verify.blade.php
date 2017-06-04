<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>
<h2>Verify Your Email Address</h2>

<div>
    <p>Hello, {{ $user->first_name }}. &nbsp; Please follow the link below to verify your email address:</p>

    <p>
        <a href="{{ URL::to('verify/email/' . $user->email_verification_token) }}">
            {{ URL::to('verify/email/' . $user->email_verification_token) }}
        </a>
    </p>
</div>

</body>

</html>
