<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushNotificationController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        // Store the subscription in the database
        PushSubscription::updateOrCreate(
            ['endpoint' => $validated['endpoint']],
            [
                'user_id' => auth()->id(),  // Assuming the user is authenticated
                'p256dh' => $validated['keys']['p256dh'],
                'auth' => $validated['keys']['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }

    public function send()
    {
        // Get the subscription for the authenticated user
        $subscription = PushSubscription::where('user_id', auth()->id())->first();

        if (!$subscription) {
            return response()->json(['success' => false, 'error' => 'No subscription found.']);
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => 'mailto:example@example.com',
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ]
        ]);

        $pushSubscription = new Subscription(
            $subscription->endpoint,
            $subscription->p256dh,
            $subscription->auth,
            'aes128gcm'
        );

        $payload = json_encode([
            'title' => 'Push Alert! 🚀',
            'body' => 'This is a test push notification.',
            'icon' => '/logo.png'
        ]);

        $webPush->queueNotification($pushSubscription, $payload);

        foreach ($webPush->flush() as $report) {
            if (!$report->isSuccess()) {
                Log::error('Push notification failed', ['report' => $report]);
                return response()->json(['success' => false, 'error' => 'Push failed.']);
            }
        }

        return response()->json(['success' => true]);
    }
}
