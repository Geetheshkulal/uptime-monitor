<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WhatsAppInvoiceBotTest extends DuskTestCase
{
    public function testSendInvoicePDF()
    {
        $payloadPath = storage_path('app/whatsapp-invoice-payload.json');

        if (!file_exists($payloadPath)) {
            $this->fail('❌ WhatsApp invoice payload JSON not found.');
            return;
        }

        $payload = json_decode(file_get_contents($payloadPath), true);

        $phoneNumber = $payload['phone'];
        $pdfPath = $payload['pdf_path'];

        if (!file_exists($pdfPath)) {
            $this->fail('❌ Invoice PDF file not found.');
            return;
        }

        // $url = "https://wa.me/{$phoneNumber}";
        $url = "https://web.whatsapp.com/send?phone={$phoneNumber}";
        

        $this->browse(function (Browser $browser) use ($url, $pdfPath) {
            $browser->visit($url)
            ->pause(4000)
            // ->clickLink('Continue to Chat')
            // ->pause(2000)
            // ->clickLink('use WhatsApp Web')
            // ->pause(15000)

            ->waitFor('span[data-icon="plus-rounded"]', 20)
            ->click('span[data-icon="plus-rounded"]')
            ->pause(2000)

        // Make the hidden input[type=file] visible
        // $browser->script("document.querySelector('input[type=file]').style.display = 'block';");
        ->script("
                Object.defineProperty(navigator, 'webdriver', {get: () => undefined});
                let input = document.querySelector('input[type=file]');
                if (input) {
                    input.style.display = 'block';
                    input.style.opacity = '1';
                    input.style.visibility = 'visible';
                    input.style.height = 'auto';
                    input.style.width = 'auto';
                }
            ");
            
        $browser->screenshot('whatsapp-file-upload');
        // Continue browser interaction separately
        $browser->attach('input[type="file"]', $pdfPath)
            ->pause(5000)
            ->waitFor('div[role="button"][aria-label="Send"]', 10)
            ->click('div[role="button"][aria-label="Send"]')
            ->pause(3000);
    });

    // Optional cleanup
    // @unlink($payloadPath);
    }
}