<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>
<h2>A user just signed up.</h2>

<div>
    <p>Name: "{{ $signup->name }}".</p>
    <p>Email: "{{ $signup->email }}".</p>
    <p>Website: "{{ $signup->website }}".</p>
    <p>Phone #: "{{ $signup->phone }}".</p>
</div>

</body>

</html>
