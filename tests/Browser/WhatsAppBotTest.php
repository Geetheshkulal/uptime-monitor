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

        $url = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)
                ->pause(5000)
                ->clickLink('Continue to Chat')
                ->pause(3000)
                ->clickLink('use WhatsApp Web')
                ->pause(10000) // wait for WhatsApp Web to load
                ->waitFor('div[role="textbox"][aria-label="Type a message"]', 20)
                ->keys('div[role="textbox"][aria-label="Type a message"]', '{enter}')
                ->pause(2000);
        });

        // Optional: delete payload after test to prevent stale data reuse
        @unlink($payloadPath);
    }
}

// namespace Tests\Browser;

// use Laravel\Dusk\Browser;
// use Tests\DuskTestCase;

// class WhatsAppBotTest extends DuskTestCase
// {
//     public function testSendWhatsAppMessage()
//     {
        
//         $phoneNumber = '919916695572';
//         $message = '✅ Your server is down! Please take action now.';
//         $url = "https://wa.me/{$phoneNumber}?text=" . urlencode($message);

//         $this->browse(function (Browser $browser) use ($url) {
//             $browser->visit($url)
//                 ->pause(5000)
//                 ->clickLink('Continue to Chat')
//                 ->pause(3000)
//                 ->clickLink('use WhatsApp Web')
//                 ->pause(10000) 
//                 ->waitFor('div[role="textbox"][aria-label="Type a message"]', 20)
//                 ->keys('div[role="textbox"][aria-label="Type a message"]', '{enter}')
//                 ->pause(2000);
//         });
//     }
// }
