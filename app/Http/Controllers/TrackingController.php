<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    // Bypass ALL middleware for this controller
    public function __construct()
    {
        $this->middleware('disable_cookies');
    }

    public function pixel(Request $request, $token)
    {
        header_remove('X-Powered-By');
        Notification::where('token', $token)->update(['status' => 'read']);
        
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
        
        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'max-age=60, private',
            'X-Frame-Options' => 'deny',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}