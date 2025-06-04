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

        $url = "https://wa.me/{$phoneNumber}";
        

        $this->browse(function (Browser $browser) use ($url, $pdfPath) {
            $browser->visit($url)
                ->pause(4000)
                ->clickLink('Continue to Chat')
                ->pause(2000)
                ->clickLink('use WhatsApp Web')
                ->pause(10000)
                ->waitFor('span[data-icon="clip"]', 20)
                ->click('span[data-icon="clip"]') // Open attachment menu
                ->pause(1000)
                ->attach('input[type="file"]', $pdfPath) // Upload PDF
                ->pause(3000)
                ->keys('div[role="textbox"][aria-label="Type a message"]', '{enter}') // Press Enter to send
                ->pause(3000);
        });

        @unlink($payloadPath); // Optional cleanup
    }
}
