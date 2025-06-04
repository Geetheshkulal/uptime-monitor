<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Invoice</title>
</head>
<body>
    <h1>Invoice</h1>
    <p><strong>Order ID:</strong> {{ $payment->transaction_id }}</p>
    <p><strong>Amount Paid:</strong> ₹{{ number_format($payment->payment_amount, 2) }}</p>
    <p><strong>Status:</strong> {{ $payment->status }}</p>
    {{-- <p><strong>User:</strong> {{ $payment->user()->name }}</p> --}}
    <p><strong>Date:</strong> {{ $payment->created_at->format('d M Y') }}</p>
</body>
</html>
