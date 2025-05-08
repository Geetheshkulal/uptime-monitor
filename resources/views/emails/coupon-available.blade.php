<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Coupon</title>
    <style>
        body {
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 30px;
            color: #333333;
        }
        .coupon-box {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .coupon-code {
            font-size: 24px;
            font-weight: bold;
            color: #ff5722;
        }
        .coupon-discount {
            font-size: 18px;
            margin-top: 10px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #777;
        }
        @media (max-width: 600px) {
            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🎁 You've Got a New Coupon!</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>We're excited to offer you a special discount on your next purchase!</p>
            
            <div class="coupon-box">
                <div class="coupon-code">{{ $coupon->code }}</div>
                <div class="coupon-discount">Save ₹{{ $coupon->value }}</div>
                @if($coupon->valid_until)
                    <div style="margin-top: 10px; font-size: 14px;">
                        Expires on: {{ \Carbon\Carbon::parse($coupon->valid_until)->format('d M Y') }}
                    </div>
                @endif
            </div>
          
          <div style="text-align: center; margin: 30px 0;">
    <a href="http://127.0.0.1:8000/premium?coupon={{ $coupon->code }}" 
       style="background: linear-gradient(135deg, #1e3c72, #2a5298); 
              color: #fff; 
              padding: 12px 25px; 
              text-decoration: none; 
              border-radius: 6px; 
              display: inline-block; 
              font-size: 16px; 
              font-weight: bold;">
        Apply Coupon & Go Premium
    </a>
</div>

            <p>Apply this coupon during checkout and enjoy the savings.</p>
        
            <p>Thanks,<br><strong>Check My Site Team</strong></p>
        </div>
      
        <div class="footer">
    <p>You are receiving this email because you signed up for updates from Check My Site.<br>
    If you wish to unsubscribe, please update your email preferences.</p>

    <p style="margin-top: 20px;"><strong>Follow us on:</strong></p>
    <p>
        <a href="https://facebook.com" target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733547.png" alt="Facebook" style="margin: 0 5px;">
        </a>
        <a href="https://twitter.com" target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733579.png" alt="Twitter" style="margin: 0 5px;">
        </a>
        <a href="https://instagram.com" target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/24/2111/2111463.png" alt="Instagram" style="margin: 0 5px;">
        </a>
        <a href="https://linkedin.com" target="_blank">
            <img src="https://cdn-icons-png.flaticon.com/24/733/733561.png" alt="LinkedIn" style="margin: 0 5px;">
        </a>
    </p>
</div>

    </div>
</body>
</html>
