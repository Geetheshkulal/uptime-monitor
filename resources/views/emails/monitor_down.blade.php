<!DOCTYPE html>
<html>
<head>
    <title>Monitor Down Alert</title>
</head>
<body>
    <!-- Tracking pixel with multiple approaches -->
    <div style="display:none;font-size:0;line-height:0;">
        <!-- Primary tracking method -->
        <img src="{{ url('/track/' . $token . '.png') }}" 
             alt="" 
             width="1" 
             height="1" 
             style="display:block;width:1px;height:1px;border:0;">
    </div>

    <h2>Alert: {{ $monitor->url }} is DOWN!</h2>
    <p>Your monitor <strong>{{ $monitor->url }}</strong> went down at {{ now() }}.</p>
    <p>Please check your website immediately.</p>
<<<<<<< HEAD
=======

    <!-- Optional visible banner -->
    <img src="{{ asset('logo.png') }}" alt="Monitor Down" style="max-width:600px;">

    <!-- Invisible tracking pixel -->
    <img src="{{ route('email.read', ['token' => $token]) }}"
         alt=""
         width="1"
         height="1"
         style="display: none;">
>>>>>>> day3
</body>
</html>