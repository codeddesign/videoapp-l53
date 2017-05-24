<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>

<div>
    <p>Name: "{{ $contact['name'] }}".</p>
    <p>Company: "{{ $contact['company'] }}".</p>
    <p>Email: "{{ $contact['email'] }}".</p>
    <p>Phone #: "{{ $contact['phone'] }}".</p>

    <p>
        {{ $contact['message'] }}
    </p>
</div>

</body>

</html>
