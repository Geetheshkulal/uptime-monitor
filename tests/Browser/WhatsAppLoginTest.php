<?php
namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\File;

class WhatsAppLoginTest extends DuskTestCase
{
    public function testWhatsappSessionLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://web.whatsapp.com');

            Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
            Storage::put('whatsapp/status.txt', 'pending');

            sleep(15); // Wait for QR code to appear

            $qrBase64 = $browser->script("
                let canvas = document.querySelector('canvas');
                return canvas ? canvas.toDataURL() : null;
            ")[0];

            if ($qrBase64) {
                Storage::put('whatsapp/qr.txt', $qrBase64);
                Log::info('[WHATSAPP SESSION] QR saved');
            }

            $browser->waitUntilMissing('canvas', 30);
            Storage::put('whatsapp/status.txt', 'loading');

            $browser->waitUsing(120, 5, function () use ($browser) {
                return $browser->script("
                    return document.querySelector('[aria-label=\"Chat list\"]') !== null;
                ")[0];
            });

            $isLoggedIn = $browser->script("
                return document.querySelector('[aria-label=\"Chat list\"]') !== null;
            ")[0];

            if ($isLoggedIn) {
                Storage::put('whatsapp/status.txt', 'connected');
                Log::info('[WHATSAPP SESSION] WhatsApp login successful!');
            } else {
                Storage::put('whatsapp/status.txt', 'pending');
                Storage::delete('whatsapp/qr.txt');
                File::deleteDirectory(storage_path('whatsapp-session'));
                Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
            }

            // Optionally keep browser open (normally tests quit after execution)
            sleep(30); // simulate alive check
        });
    }
}
