<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AdminWhatsAppController extends Controller
{
    public function AdminWhatsappLogin()
    {
        return view('pages.admin.Whatsapplogin');
    }

    public function fetchQr()
    {
        $qrPath = storage_path('app/whatsapp/qr.txt');
        $statusPath = storage_path('app/whatsapp/status.txt');

        $qr = File::exists($qrPath) ? File::get($qrPath) : null;
        $status = File::exists($statusPath) ? trim(File::get($statusPath)) : 'pending';

        return response()->json([
            'qr' => $qr,
            'status' => $status,
        ]);
    }

    public function disconnectWhatsApp()
    {
        try {
            // Remove session folder
            File::deleteDirectory(storage_path('whatsapp-session'));
    
            // Remove QR and status
            File::delete(storage_path('app/whatsapp/qr.txt'));
            File::put(storage_path('app/whatsapp/status.txt'), 'pending');
    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('[WHATSAPP DISCONNECT ERROR] ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    

}
