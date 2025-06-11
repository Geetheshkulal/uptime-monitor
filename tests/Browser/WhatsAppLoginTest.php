<?php
namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\File;
use Facebook\WebDriver\Exception\TimeoutException;



class WhatsAppLoginTest extends DuskTestCase
{
    public function testWhatsappSessionLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://web.whatsapp.com');
            Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
            Storage::put('whatsapp/status.txt', 'pending');
        
            sleep(15);
        
            $qrBase64 = $browser->script("
                let canvas = document.querySelector('canvas');
                return canvas ? canvas.toDataURL() : null;
            ")[0];
        
            if ($qrBase64) {
                Storage::put('whatsapp/qr.txt', $qrBase64);
                Log::info('[WHATSAPP SESSION] QR code saved.');
            } else {
                Storage::put('whatsapp/status.txt', 'pending');
                Storage::delete('whatsapp/qr.txt');
                File::deleteDirectory(storage_path('whatsapp-session'));
                Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
            }
        
            try {
                $qrScanned = $browser->waitUsing(30, 2, function () use ($browser) {
                    return $browser->script("return document.querySelector('canvas') === null;")[0];
                });
            } catch (TimeoutException $e) {
                // QR was never scanned
                Storage::put('whatsapp/status.txt', 'disconnected');
                Log::warning('[WHATSAPP SESSION] QR not scanned in time.');
                return;
            }
        
            // QR was scanned, now waiting for login
            Storage::put('whatsapp/status.txt', 'loading');
            Log::info('[WHATSAPP SESSION] QR scanned. Waiting for login...');
        
            try {
                $isLoggedIn = $browser->waitUsing(120, 5, function () use ($browser) {
                    return $browser->script("
                        return document.querySelector('[aria-label=\"Chat list\"]') !== null;
                    ")[0];
                });
            } catch (TimeoutException $e) {
                Storage::put('whatsapp/status.txt', 'disconnected');
                Log::warning('[WHATSAPP SESSION] Login failed after QR scanned.');
                return;
            }
        
            Storage::put('whatsapp/status.txt', 'connected');
            Log::info('[WHATSAPP SESSION] WhatsApp login successful!');
        });
    }
}

// class WhatsAppLoginTest extends DuskTestCase
// {
//     public function testWhatsappSessionLogin()
//     {
//         $this->browse(function (Browser $browser) {
//             $browser->visit('https://web.whatsapp.com');

//             Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
//             Storage::put('whatsapp/status.txt', 'pending');

//             sleep(15); 

//             $qrBase64 = $browser->script("
//                 let canvas = document.querySelector('canvas');
//                 return canvas ? canvas.toDataURL() : null;
//             ")[0];

//             if ($qrBase64) {
//                 Storage::put('whatsapp/qr.txt', $qrBase64);
//                 Log::info('[WHATSAPP SESSION] QR saved');
//             }else{
//                 Log::warning('[WHATSAPP SESSION] QR code not found');
//             }

//             $browser->waitUntilMissing('canvas', 30);
//             Storage::put('whatsapp/status.txt', 'loading');

//             $browser->waitUsing(120, 5, function () use ($browser) {
//                 return $browser->script("
//                     return document.querySelector('[aria-label=\"Chat list\"]') !== null;
//                 ")[0];
//             });

//             $isLoggedIn = $browser->script("
//                 return document.querySelector('[aria-label=\"Chat list\"]') !== null;
//             ")[0];

//             if ($isLoggedIn) {
//                 Storage::put('whatsapp/status.txt', 'connected');
//                 Log::info('[WHATSAPP SESSION] WhatsApp login successful!');
//             } else {
//                 Storage::put('whatsapp/status.txt', 'pending');
//                 Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
//             }

           
//             sleep(30);
//         });
//     }
// }
