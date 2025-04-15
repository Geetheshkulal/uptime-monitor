<!DOCTYPE html>
<html>
<head>
    <title>Monitor Down Alert</title>
</head>
<body>
    <h2>Alert: {{ $monitor->url }} is DOWN!</h2>
    <p>Your monitor <strong>{{ $monitor->url }}</strong> went down at {{ now() }}.</p>
    <p>Please check your website immediately.</p>

    <!-- Optional visible banner -->
    <img src="{{ asset('logo.png') }}" alt="Monitor Down" style="max-width:600px;">

    <!-- Invisible tracking pixel -->
    <img src="{{ route('email.read', ['token' => $token]) }}"
         alt=""
         width="1"
         height="1"
         style="display: none;">
</body>
</html>
