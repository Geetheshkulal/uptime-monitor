<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use App\Jobs\WhatsAppLogin;



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
        $userName = storage_path('app/whatsapp/user_name.txt');


        $qr = File::exists($qrPath) ? File::get($qrPath) : null;
        $status = File::exists($statusPath) ? trim(File::get($statusPath)) : 'pending';
        $userName = File::exists($userName) ? trim(File::get($userName)) : 'N/A';

        return response()->json([
            'qr' => $qr,
            'status' => $status,
            'userName' =>$userName,
        ]);
    }

    public function disconnectWhatsApp()
    {
        try {
            // Remove session folder
            File::deleteDirectory(storage_path('whatsapp-session'));
    
            // Remove QR and status
            File::delete(storage_path('app/whatsapp/qr.txt'));
            File::delete(storage_path('app/whatsapp/user_name.txt'));
            File::delete(storage_path('app/whatsapp/profile_photo.png'));
            File::put(storage_path('app/whatsapp/status.txt'), 'pending');

    
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('[WHATSAPP DISCONNECT ERROR] ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function triggerLogin()
    {
        try {
        // Safer: use Process to run Artisan Dusk (non-blocking)
                WhatsAppLogin::dispatch();
                $output = Artisan::output();
                Log::info('[WHATSAPP LOGIN] Artisan command executed: ' . $output);
        
            return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
}

public function retryWhatsApp()
{
    try {
        // Remove session folder
        File::deleteDirectory(storage_path('whatsapp-session'));

        File::put(storage_path('app/whatsapp/status.txt'), 'pending');

        return response()->json(['success' => true]);
    } catch (\Exception $e) {
        Log::error('[WHATSAPP retry ERROR] ' . $e->getMessage());
        return response()->json(['success' => false, 'error' => $e->getMessage()]);
    }
}

    public function serveProfileImage()
    {
        $path = storage_path('app/whatsapp/profile_photo.png');

        if (File::exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }
 
}
