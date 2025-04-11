<!DOCTYPE html>
<html>
<head>
    <title>Monitor Down Alert</title>
</head>
<body>
    <h2>Alert: {{ $monitor->url }} is DOWN!</h2>
    <p>Your monitor <strong>{{ $monitor->url }}</strong> went down at {{ now() }}.</p>
    <p>Please check your website immediately.</p>

    {{-- Invisible tracking pixel --}}
    <img src="{{ url('/track/' . $token) }}" width="1" height="1" style="display: none;" alt="" />
</body>
</html>
