<?php

namespace App\Http\Controllers;

use App\Models\PushSubscription;
use Illuminate\Http\Request;

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
}
