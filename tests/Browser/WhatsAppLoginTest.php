<?php

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WhatsAppLoginTest extends DuskTestCase
{
    public function testLoginToWhatsApp()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://web.whatsapp.com')
                ->pause(10000); // Wait for QR code to load

            echo "Please scan the QR code within 30 seconds...\n";

            $browser->pause(30000); // Wait manually to scan QR

            // Now logged in — you can interact or store session
            $browser->screenshot('whatsapp_logged_in');

            // Optionally: Keep browser open for session reuse
        });
    }
}