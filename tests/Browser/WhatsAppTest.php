<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Storage;

class WhatsAppTest extends DuskTestCase
{
    /** @test */
    public function it_opens_whatsapp_with_prefilled_message()
    {
        // Read details from file saved by MonitorJob
        if (!Storage::disk('local')->exists('whatsapp-details.json')) {
            $this->fail('whatsapp-details.json file not found.');
            return;
        }

        $details = json_decode(Storage::disk('local')->get('whatsapp-details.json'), true);

        if (empty($details['phone']) || empty($details['url']) || empty($details['type'])) {
            $this->fail('Monitor details not set properly.');
            return;
        }

 $this->browse(function (Browser $browser) use ($details) {
    $phone = preg_replace('/[^0-9]/', '', $details['phone']);
    $message = "🚨 Alert!\n"
             . "Monitor Failed:\n"
             . "URL: {$details['url']}\n"
             . "Type: {$details['type']}\n"
             . "Time: {$details['time']}";

    $url = 'https://web.whatsapp.com/send?phone=' . $phone . '&text=' . urlencode($message);

    $browser->visit($url)
        ->pause(15000) // wait for WhatsApp Web to fully load and chat to appear
        ->screenshot('whatsapp-web-loaded')
        ->waitFor('div[contenteditable="true"][data-tab]', 20)
        ->pause(2000)

        // Clear and set message via JS to ensure proper behavior
        ->script("document.querySelector('div[contenteditable=\"true\"][data-tab]').innerHTML = '';");
    $browser->pause(2000);

    // Click the actual send button by aria-label
    $browser->script("
        const sendBtn = document.querySelector('button[aria-label=\"Send\"]');
        if (sendBtn) sendBtn.click();
    ");

    $browser->pause(3000)->screenshot('whatsapp-message-sent');
});

    
}
}