<?php

namespace App\Http\Controllers;

use App\Jobs\RunWhatsAppInvoiceBotTest;
use App\Models\Subscriptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Payment;
use Illuminate\Support\Facades\Redirect;
use App\Models\CouponUser;
use App\Models\CouponCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;


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

        $user=auth()->user();
        $userId=$user->id;

    

        $subscription = Subscriptions::with('payment')->find($validated['subscription_id']);

        $orderId = 'order_' . rand(1111111111, 9999999999);

        $orderAmount = $subscription->amount;

        $url = "https://sandbox.cashfree.com/pg/orders";

        $couponCode = CouponUser::with(['coupon' => function ($query) {
                    $now = now();
                    $query->where('is_active', true)
                        ->where(function ($q) use ($now) {
                            $q->whereNull('valid_from')->orWhere('valid_from', '<=', $now);
                        })
                        ->where(function ($q) use ($now) {
                            $q->whereNull('valid_until')->orWhere('valid_until', '>=', $now);
                        });
                }])
                ->where('user_id', $userId)
                ->first()?->coupon;
        

        $couponCodeValue = null;
        $couponCodeText = null;
        $discount_type = null;

        if ($couponCode && ($couponCode->subscription->id ===$subscription->id)) {
            $couponCodeValue = $couponCode->value;
            $couponCodeText = $couponCode->code;
            $discount_type = $couponCode->discount_type;

           
            Log::info('type'.(string)max(0, $orderAmount - ($couponCode->value/100)*$orderAmount));
            $orderAmount =($couponCode->discount_type === 'flat')? max(0, $orderAmount - $couponCode->value): round(max(0, $orderAmount - ($couponCode->value/100)*$orderAmount));
        }

        $headers = [
            "Content-Type: application/json",
            "x-api-version: 2022-01-01",
            "x-client-id: ".config('services.cashfree.key'),
            "x-client-secret: ".config('services.cashfree.secret'),
        ];


       
        $data = json_encode([
            'order_id' => $orderId,
            'order_amount' => $orderAmount,
            "order_currency" => "INR",
            "order_note" => "subscription_id:" . $validated['subscription_id'] . "|user_id:" . auth()->id()."|coupon:" . $couponCodeText.
            "|value:" . $couponCodeValue."|discount_type:".$discount_type,
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

        Log::error('Cashfree API Response', ['response' => $resp]);


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

   

    protected function verifyAndProcessPayment($orderId)
    {
        $existingPayment = Payment::where('transaction_id', $orderId)->first();
        if ($existingPayment) {
            return $existingPayment;
        }

        $headers = [
            "Content-Type: application/json",
            "x-api-version: 2022-01-01",
            "x-client-id: " .env('CASHFREE_API_KEY'),
            "x-client-secret: ".env('CASHFREE_API_SECRET'),
        ];

        // First get order details to verify status
        $curl = curl_init("https://sandbox.cashfree.com/pg/orders/{$orderId}");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($curl);
        curl_close($curl);
        $orderDetails = json_decode($response, true);
        Log::info("Cashfree Order Details:", $orderDetails);

        // Verify the payment status
        if (($orderDetails['order_status'] ?? '') !== 'PAID') {
            return null;
        }

        // Now get payment details to get payment method
        $curl = curl_init("https://sandbox.cashfree.com/pg/orders/{$orderId}/payments");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $paymentResponse = curl_exec($curl);
        curl_close($curl);
        $paymentDetails = json_decode($paymentResponse, true);
        Log::info("Cashfree Payment Details:", $paymentDetails);

        // Get the payment method from the first payment (if available)
        $paymentMethod = 'unknown';
        if (!empty($paymentDetails[0]['payment_method'])) {
            // Get the first key of the payment_method array which is the method type
            $methodTypes = array_keys($paymentDetails[0]['payment_method']);
            if (!empty($methodTypes[0])) {
                $paymentMethod = $methodTypes[0]; // 'netbanking', 'card', 'upi', etc.
            }
        }

        // Fetch order details from Cashfree
        $headers = [
            "Content-Type: application/json",
            "x-api-version: 2022-01-01",
            "x-client-id: ".config('services.cashfree.key'),
            "x-client-secret: ".config('services.cashfree.secret'),
        ];

    $orderNote = $orderDetails['order_note'] ?? '';
    $subscriptionId = null;
    $userId = null;
    $couponCode = null;
    $couponValue = null;
    $discount_type = null;

    

    
    $parts = explode('|', $orderNote);
    foreach ($parts as $part) {
        if (Str::startsWith($part, 'subscription_id:')) {
            $subscriptionId = str_replace('subscription_id:', '', $part);
        }
        if (Str::startsWith($part, 'user_id:')) {
            $userId = str_replace('user_id:', '', $part);
        }
        if (Str::startsWith($part, 'coupon:')) {
            $couponCode = str_replace('coupon:', '', $part);
        }
        if (Str::startsWith($part, 'value:')) {
            $couponValue = (float) str_replace('value:', '', $part);
        }
        if (Str::startsWith($part, 'discount_type:')) {
            $discount_type = str_replace('discount_type:', '', $part);
        }
    }

    if (!$subscriptionId || !$userId) {
        return null;
    }


    $paymentStatus = $paymentDetails[0]['payment_status'] ?? 'PENDING'; 
    $paymentAmount = $paymentDetails[0]['payment_amount'] ?? $orderDetails['order_amount']; 

    // Process payment
    return \DB::transaction(function () use ($orderId, $userId, $subscriptionId, $paymentMethod,$paymentAmount,$paymentStatus,$couponCode,$couponValue, $orderDetails,$discount_type) {
        $user = \App\Models\User::find($userId);
        
        if (!$user) {
            return null;
        }
       Log::info('discount type new:'.$discount_type);

       $discount_type = in_array($discount_type, ['fixed', 'percentage']) ? $discount_type : null;

        // Create payment record
        $payment = Payment::create([
            'coupon_code' => $couponCode ?: null,
            'coupon_value' => $couponValue > 0 ? $couponValue : null,
            'payment_amount' => $paymentAmount,
            'discount_type'=> $discount_type,
            'status' => 'active',
            'user_id' => $userId,
            'payment_status' => $paymentStatus,
            'transaction_id' => $orderId,
            'payment_type' => $paymentMethod,
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'subscription_id' => $subscriptionId,
            'city' => $user->city, 
            'state' => $user->state, 
            'pincode' => $user->pincode, 
            'country' => $user->country,
            'address' => $user->address,
        ]);

        // Update user status
        // $user->update([
        //     'status' => 'paid',
        //     'premium_end_date' => now()->addMonth(),
        // ]);

        if ($paymentStatus === 'SUCCESS') {
            $user->update([
                'status' => 'paid',
                'premium_end_date' => now()->addMonth(),
            ]);
        }



        $pdf = Pdf::loadView('pdf.invoice', ['payment' => $payment]);

        // Save to storage/app/public/invoices/...
        $filename = "invoice_{$payment->transaction_id}.pdf";
        Storage::put("public/invoices/{$filename}", $pdf->output());

        // payload for invoice
        $payloadData=[
            'phone'=>$user->phone,
            'pdf_path'=>storage_path("app/public/invoices/{$filename}"),
        ];

        file_put_contents(storage_path('app/whatsapp-invoice-payload.json'), json_encode($payloadData));


         if ($paymentStatus === 'SUCCESS') {
            $user->update([
                'status' => 'paid',
                'premium_end_date' => now()->addMonth(),
            ]);
            
            // Dispatch only here
            try {
                RunWhatsAppInvoiceBotTest::dispatch();
                Log::info('WhatsAppInvoiceBotTest job dispatched successfully.');
            } catch (\Throwable $e) {
                Log::error('Failed to dispatch WhatsAppInvoiceBotTest job: ' . $e->getMessage());
            }
        }



        return $payment;
    });
}

 
    public function status(Request $request)
    {
        $user = auth()->user();
        $hasActiveSubscription = ($user->status == 'paid');
        
        return response()->json([
            'payment_success' => $hasActiveSubscription,
            'payment_end_date'=>$user->premium_end_date,
            'status' => $user->status,  
        ]);
    }
}