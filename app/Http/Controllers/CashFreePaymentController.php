<?php

namespace App\Http\Controllers;

use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Payment;

class CashFreePaymentController extends Controller
{
    // View Payment Page
    public function create(Request $request)
    {
        return view('payment-create');
    }

    // Process payment with cashfree
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
            "x-client-secret: " . env('CASHFREE_API_SECRET')
        ];

        $data = json_encode([
            'order_id' => $orderId,
            'order_amount' => $subscription->amount,
            "order_currency" => "INR",
            "order_note" => "subscription_id:" . $validated['subscription_id'] . "|user_id:" . auth()->id(),
            "customer_details" => [
                "customer_id" => 'customer_' . rand(111111111, 999999999),
                "customer_name" => $validated['name'],
                "customer_email" => $validated['email'],
                "customer_phone" => $validated['mobile'],
            ],
            "order_meta" => [
                "return_url" => route('success', [
                    'order_id' => $orderId,
                    'order_token' => Str::random(32),
                ]),
                "notify_url" => route('webhook')
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

        $paymentLink = json_decode($resp)->payment_link;
        
        // Return JSON response for the new window
        return response()->json([
            'payment_link' => $paymentLink,
            'order_id' => $orderId
        ]);
    }

    // Handle payment success
    public function success(Request $request)
    {
        $orderId = $request->query('order_id');

        // Verify payment and update records
        $this->verifyAndProcessPayment($orderId);

        // Close the window and notify parent
        return view('pages.payment-success');
    }

    // Webhook for payment verification
    public function webhook(Request $request)
    {
        $data = $request->all();
        Log::info('Cashfree Webhook:', $data);

        if (isset($data['orderId'])) {
            $this->verifyAndProcessPayment($data['orderId']);
        }

        return response()->json(['status' => 'success']);
    }

    // Common payment verification logic
    protected function verifyAndProcessPayment($orderId)
    {
        // First check if we already processed this payment
        $existingPayment = Payment::where('transaction_id', $orderId)->first();
        if ($existingPayment) {
            return $existingPayment;
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
        Log::info("Order Details: ", $orderDetails);

        // Verify the payment status
        if (($orderDetails['order_status'] ?? '') !== 'PAID') {
            return null;
        }

        // Extract subscription_id and user_id from order_note
        $orderNote = $orderDetails['order_note'] ?? '';
        $subscriptionId = null;
        $userId = null;
        
        $parts = explode('|', $orderNote);
        foreach ($parts as $part) {
            if (Str::startsWith($part, 'subscription_id:')) {
                $subscriptionId = str_replace('subscription_id:', '', $part);
            }
            if (Str::startsWith($part, 'user_id:')) {
                $userId = str_replace('user_id:', '', $part);
            }
        }

        if (!$subscriptionId || !$userId) {
            return null;
        }

        // Process payment
        return \DB::transaction(function () use ($orderId, $userId, $subscriptionId, $orderDetails) {
            $user = \App\Models\User::find($userId);
            
            if (!$user) {
                return null;
            }

            // Create payment record
            $payment = Payment::create([
                'status' => 'active',
                'user_id' => $userId,
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

            return $payment;
        });
    }

    public function status(Request $request)
    {
        $user = auth()->user();
        $hasActiveSubscription = ($user->status == 'paid');
        
        return response()->json([
            'payment_success' => $hasActiveSubscription
        ]);
    }
}