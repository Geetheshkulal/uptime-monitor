<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class WhatsAppLoginTest extends DuskTestCase
{
    public function testLoginAndExtractQr()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://web.whatsapp.com');

            $browser->pause(30000); 

            Log::info('[DUSK] Trying to extract QR code');

            $qrBase64 = $browser->script("
                let canvas = document.querySelector('canvas');
                return canvas ? canvas.toDataURL() : null;
            ")[0];

            if ($qrBase64) {
                Storage::put('whatsapp/qr.txt', $qrBase64);
                Log::info('[DUSK] QR extracted and saved. Size: ' . strlen($qrBase64));
            }else{
                Log::warning('[DUSK] QR code not found (canvas was null)');
            }

            while (true) {
                sleep(10); // Keeps it running
            }
        });
    }
}
