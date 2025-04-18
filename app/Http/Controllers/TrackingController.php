<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

//Controller for implementing tracking pixel in mail for read reciept
class TrackingController extends Controller
{
    // Bypass ALL middleware for this controller
    public function __construct()
    {
        $this->middleware('disable_cookies');
    }

    //Create a base 64 pixel and log seen in the db as soon as it is requested
    public function pixel(Request $request, $token)
    {
        header_remove('X-Powered-By'); //remove header
        Notification::where('token', $token)->update(['status' => 'read']); //update status to 'read'
        
        //create a base64 1x1 image
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=');
        
        //respond with the image
        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'max-age=60, private',
            'X-Frame-Options' => 'deny',
            'X-Robots-Tag' => 'noindex, nofollow',
        ]);
    }
}