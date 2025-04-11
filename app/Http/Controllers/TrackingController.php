<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Response;

class TrackingController extends Controller
{
    //

    public function pixel(Request $request, $token)
    {
        // You can log or store the tracking hit here
        Log::info("Tracking pixel hit for ID: $token from IP: " . $request->ip());

        $notification = Notification::where('token', $token)->first();

        if ($notification) {
            $notification->status = 'read'; // or whatever status you use
            $notification->save();
        }

        
        $gif = base64_decode(
            
            'R0lGODlhZABkAKECAAAAAP8AAP///yH5BAEAAAIALAAAAABkAGQAAAJDlI+py+0Po5y02ouz3rz7D4biSJbmiabqyrbuC8fyrBu33gvH8grrcXs8hzv9oHADs='

        );

        return Response::make($gif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => strlen($gif),
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
