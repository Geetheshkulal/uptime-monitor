<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <script>
        window.onload = function() {
            // Notify the parent window that payment is complete
            if (window.opener) {
                window.opener.postMessage('payment_completed', '*');
            }
            // Close this window after a short delay
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .message-box {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 400px;
        }
        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        .success {
            color: #4CAF50;
        }
        .processing {
            color: #FFC107;
        }
    </style>
</head>
<body>
    <div class="message-box">
        <div class="icon success">✓</div>
        <h2>Payment Successful!</h2>
        <p>Your transaction was completed successfully.</p>
        <p>This window will close automatically.</p>
    </div>
</body>
</html>