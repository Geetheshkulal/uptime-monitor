<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;

class AdminWhatsAppController extends Controller
{
    public function AdminWhatsappLogin()
    {
        return view('pages.admin.Whatsapplogin');
    }

    public function fetchQr()
    {
        $path = storage_path('app/whatsapp/qr.txt');
        if (!file_exists($path)) {
            return response()->json(['qr' => null]);
        }

        $qr = File::get($path);
        return response()->json(['qr' => $qr]);
    }

    public function liveStatus()
    {
        try {
            Artisan::call('whatsapp:check-status');
            $output = Artisan::output();
    
            // Optional: you can read a flag, or parse output to decide
            // But for simplicity, just return "assumed connected"
            return response()->json([
                'status' => 'connected', // or dynamically set
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

}
