<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Payment;

class CashFreePaymentController extends Controller
{
    public function create(Request $request)
    {
        return view('payment-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|min:3',
            'email' => 'required',
            'mobile' => 'required',
            'subscription_id' => 'required'
        ]);

        $subscription = Subscriptions::with('payment')->find($validated['subscription_id']);

        $orderId = 'order_' . rand(1111111111, 9999999999);

        $url = "https://sandbox.cashfree.com/pg/orders";

        $headers = [
            "Content-Type: application/json",
            "x-api-version: 2022-01-01",
            "x-client-id: " . env('CASHFREE_API_KEY'),
            "x-client-secret: " .env('CASHFREE_API_SECRET')
        ];

        $data = json_encode([
            'order_id' => $orderId,
            'order_amount' => $subscription->amount,
            "order_currency" => "INR",
            "order_note" => "subscription_id:" . $validated['subscription_id'], // Add this line
            "customer_details" => [
                "customer_id" => 'customer_' . rand(111111111, 999999999),
                "customer_name" => $validated['name'],
                "customer_email" => $validated['email'],
                "customer_phone" => $validated['mobile'],
            ],
            "order_meta" => [
                "return_url" => 'http://127.0.0.1:8000/cashfree/payments/success/?order_id={order_id}&order_token={order_token}'
            ],
        ]);

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($curl);
        curl_close($curl);

        return redirect()->to(json_decode($resp)->payment_link);
    }

    public function success(Request $request)
{
    $orderId = $request->query('order_id');
    $user = auth()->user();

    // First check if we already processed this payment
    $existingPayment = Payment::where('transaction_id', $orderId)->first();
    if ($existingPayment) {
        return redirect()->route('monitoring.dashboard')->with('info', 'Payment was already processed successfully.');
    }

    // Fetch order details from Cashfree
    $headers = [
        "Content-Type: application/json",
        "x-api-version: 2022-01-01",
        "x-client-id: " . env('CASHFREE_API_KEY'),
        "x-client-secret: ". env('CASHFREE_API_SECRET'),
    ];

    $curl = curl_init("https://sandbox.cashfree.com/pg/orders/{$orderId}");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($curl);
    curl_close($curl);

    $orderDetails = json_decode($response, true);
    Log::info(["Order Details: ", $orderDetails]);

    // Verify the payment status with Cashfree
    if (($orderDetails['order_status'] ?? '') !== 'PAID') {
        return redirect()->route('monitoring.dashboard')->with('error', 'Payment not confirmed by Cashfree.');
    }

    // Extract subscription_id from order_note
    $orderNote = $orderDetails['order_note'] ?? '';
    $subscriptionId = null;
    if (Str::startsWith($orderNote, 'subscription_id:')) {
        $subscriptionId = str_replace('subscription_id:', '', $orderNote);
    }

    if (!$subscriptionId) {
        return redirect()->route('monitoring.dashboard')->with('error', 'Unable to verify subscription. Please contact support.');
    }

    // Use transaction to ensure atomic operation
    \DB::transaction(function () use ($orderId, $user, $subscriptionId, $orderDetails) {
        // Create payment record
        $payment = Payment::create([
            'status' => 'active',
            'user_id' => $user->id,
            'payment_status' => 'paid',
            'transaction_id' => $orderId,
            'payment_type' => 'upi',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'subscription_id' => $subscriptionId,
      
        ]);

        // Update user status
        $user->update([
            'status' => 'paid',
            'premium_end_date' => now()->addMonth(),
        ]);

        // Log activity
        activity()
            ->performedOn($payment)
            ->causedBy($user)
            ->inLog('payment')
            ->event('payment-success')
            ->withProperties([
                'user_name' => $user->name,
                'email' => $user->email,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
                'payment_type' => $payment->payment_type,
                'premium_until' => $user->premium_end_date,
            ])
            ->log('User completed a premium payment successfully');
    });

    return redirect()->route('monitoring.dashboard')->with('success', 'Payment successful! Features unlocked.');
}
}
