<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
</head>

<body>

<div>
    <p>Name: "{{ $sender['name'] }}".</p>
    <p>Company: "{{ $sender['company'] }}".</p>
    <p>Email: "{{ $sender['email'] }}".</p>
    <p>Phone #: "{{ $sender['phone'] }}".</p>

    @if (!is_null($sender['website']))
        <p>Website #: "{{ $sender['website'] }}".</p>
    @endif

    @if (!is_null($sender['pageviews']))
        <p>Page views #: "{{ $sender['pageviews'] }}".</p>
    @endif

    @if (!is_null($sender['message']))
        <p>
            {{ $sender['message'] }}
        </p>
    @endif
</div>

</body>

</html>
