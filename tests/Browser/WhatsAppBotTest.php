<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WhatsAppBotTest extends DuskTestCase
{
    public function testSendWhatsAppMessage()
    {
        $payloadPath = storage_path('app/whatsapp-payload.json');

        if (!file_exists($payloadPath)) {
            $this->fail('❌ WhatsApp payload JSON not found.');
            return;
        }

        $payload = json_decode(file_get_contents($payloadPath), true);

        $phoneNumber = $payload['phone'];
        // $message = $payload['message'];

        // <<<TEXT is PHP's "nowdoc" or "heredoc" syntax, specifically a heredoc, used to define multi-line strings
        $message = <<<TEXT
                {$payload['message']}

                🔗 URL: {$payload['url']}
                📡 Type: {$payload['type']}
                TEXT;

        $url = $url = "https://web.whatsapp.com/send?phone={$phoneNumber}&text=" . urlencode($message);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                ->waitFor('button[aria-label="Send"]', 10)
                ->click('button[aria-label="Send"]')
                ->pause(2000);
        });

        // Optional: delete payload after test to prevent stale data reuse
        @unlink($payloadPath);
    }
}

