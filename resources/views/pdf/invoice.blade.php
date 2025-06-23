<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $payment->transaction_id }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 13px; /* smaller base font size */
        }
        .bill-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
        }
        .bill-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e0e0e0;
        }
        .company-info {
            text-align: left;
        }
        .company-name {
            font-size: 18px; /* smaller */
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 3px;
        }
        .company-tagline {
            font-size: 11px; /* smaller */
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        .company-address {
            font-size: 11px; /* smaller */
            line-height: 1.4;
            color: #555;
        }
        .bill-title {
            font-size: 20px; /* smaller */
            font-weight: 300;
            color: #3498db;
            margin-bottom: 6px;
            text-align: right;
        }
        .bill-meta {
            text-align: right;
            font-size: 11px; /* smaller */
        }
        .bill-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .bill-from, .bill-to {
            flex: 1;
            padding: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 12px; /* smaller */
        }
        .bill-to {
            margin-left: 15px;
            background: #f1f8fe;
        }
        .section-title {
            font-size: 14px; /* smaller */
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            padding-bottom: 3px;
            border-bottom: 1px solid #eee;
        }
        .bill-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 12px; /* smaller */
        }
        .bill-table thead th {
            background: #3498db;
            color: white;
            padding: 8px 10px;
            text-align: left;
            font-weight: 500;
        }
        .bill-table tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #eee;
        }
        .bill-table tfoot td {
            padding: 8px 10px;
            font-weight: 600;
            background: #f9f9f9;
        }
        .total-box {
            width: 300px;
            padding: 10px;
            background: #f1f8fe;
            border-radius: 5px;
            margin-left: auto;
            font-size: 12px; /* smaller */
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .grand-total {
            font-size: 16px; /* smaller */
            color: #3498db;
            border-top: 1px solid #ddd;
            padding-top: 8px;
            margin-top: 8px;
        }
        .signature-area {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            font-size: 11px; /* smaller */
        }
        .signature-line {
            border-top: 1px solid #ccc;
            width: 200px;
            margin-top: 40px;
            text-align: center;
            padding-top: 5px;
            font-size: 12px;
        }
        .footer-note {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            font-size: 11px; /* smaller */
            color: #7f8c8d;
            text-align: center;
        }
        .footer-note .thank-you {
            font-size: 14px; /* smaller */
            color: #3498db;
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="bill-container">
        <table width="100%" style="margin-bottom: 10px; border-bottom: 1px solid #e0e0e0; padding-bottom: 10px;">
            <tr>
                <td style="text-align: left; vertical-align: top;">
                    <div class="company-name">CheckMySite</div>
                    <div class="company-tagline">Website Monitoring Solutions</div>
                    <div class="company-address">
                        3rd Floor, VSK Towers, Kottara Chowki, Mangaluru, Karnataka<br>
                        India<br>
                        GSTIN: 22ABCDE1234F1Z5<br>
                        Phone: +91 8073462033<br>
                        Email: billing@checkmysite.com
                    </div>
                </td>
                <td style="text-align: right; vertical-align: top;">
                    <div class="bill-title">INVOICE</div>
                    <div class="bill-meta">
                        <div><strong>Invoice #:</strong> INV-{{ $payment->transaction_id }}</div>
                        <div><strong>Date:</strong> {{ now()->format('d M Y') }}</div>
                    </div>
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-bottom: 10px;">
            <tr>
                <td width="50%" style="background: #f9f9f9; padding: 8px;">
                    <div class="section-title">From:</div>
                    <div>
                        <strong>CheckMySite</strong><br>
                        3rd Floor, VSK Towers Kottara Chowki<br>
                        Mangalore, Karnataka 575006<br>
                        India<br>
                        GSTIN: 22ABCDE1234F1Z5
                    </div>
                </td>
                <td width="50%" style="background: #f1f8fe; padding: 8px;">
                    <div class="section-title">Bill To:</div>
                    <div>
                        <strong>{{ $payment->user->name }}</strong><br>
                        @if($payment->address){{ $payment->address }}<br>@endif
                        @if($payment->city){{ $payment->city }},@endif
                        @if($payment->state){{ $payment->state }}@endif
                        @if($payment->pincode)- {{ $payment->pincode }}<br>@endif
                        @if($payment->country){{ $payment->country }}@endif
                    </div>
                </td>
            </tr>
        </table>

        <table class="bill-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->subscription->name }} Subscription</td>
                    <td>₹{{ number_format($payment->subscription->amount, 2) }}</td>
                </tr>
                @if($payment->coupon_code)
                <tr>
                    <td>
                        Coupon Discount ({{ $payment->coupon_code }})
                        @if($payment->discount_type === 'percentage')
                            ({{ $payment->coupon_value }}%)
                        @endif
                    </td>
                    <td>
                        -₹{{ 
                            $payment->discount_type === 'flat' 
                            ? number_format($payment->coupon_value, 2)
                            : number_format(($payment->subscription->amount * $payment->coupon_value / 100), 2)
                        }}
                    </td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Total</strong></td>
                    <td><strong>₹{{ number_format($payment->payment_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <table width="300" align="right" cellpadding="8" style="background: #f1f8fe; font-size: 12px;">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">₹{{ number_format($payment->subscription->amount, 2) }}</td>
            </tr>
            @if($payment->coupon_code)
            <tr>
                <td>Discount:</td>
                <td style="text-align: right;">
                    -₹{{ 
                        $payment->discount_type === 'flat' 
                        ? number_format($payment->coupon_value, 2)
                        : number_format(($payment->subscription->amount * $payment->coupon_value / 100), 2)
                    }}
                </td>
            </tr>
            @endif
            <tr>
                <td>Tax (0%):</td>
                <td style="text-align: right;">₹0.00</td>
            </tr>
            <tr>
                <td><strong>Total:</strong></td>
                <td style="text-align: right;"><strong>₹{{ number_format($payment->payment_amount, 2) }}</strong></td>
            </tr>
        </table>

        <div style="margin-top: 20px; font-size: 12px;">
            <div><strong>Payment Method:</strong> {{ strtoupper($payment->payment_type) }}</div>
            <div><strong>Payment Status:</strong> {{ strtoupper($payment->payment_status) }}</div>
            <div><strong>Subscription Period:</strong> 
                {{ \Carbon\Carbon::parse($payment->start_date)->format('d M Y') }} to 
                {{ \Carbon\Carbon::parse($payment->end_date)->format('d M Y') }}
            </div>
        </div>

        <table width="100%" style="margin-top: 40px; font-size: 11px;">
            <tr>
                <td style="border-top: 1px solid #ccc; width: 200px; padding-top: 5px; text-align: center; border-top: 1px solid #ccc;font-size: 12px;">

                    Customer Signature
                </td>
                <td style="border-top: 1px solid #ccc; width: 200px; padding-top: 5px; text-align: center;font-size: 12px;">
                    For CheckMySite
                </td>
            </tr>
        </table>

        <div class="footer-note">
            <div class="thank-you">Thank you for your business!</div>
        </div>
    </div>
</body>
</html>