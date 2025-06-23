<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class WhatsAppTest extends DuskTestCase
{
    /**
     * Test sending a WhatsApp message via wa.me link.
     *
     * @return void
     */
    public function testSendMessageToNumber()
    {
        $phoneNumber = '7356554460';  // Replace with the target phone number with country code (no + or spaces)
        $message = 'Hello from Ananth!';  // Your message here

        $this->browse(function (Browser $browser) use ($phoneNumber, $message) {
            // Visit WhatsApp Web main URL (this loads saved session)
            $browser->visit('https://web.whatsapp.com')
                ->waitFor('.app', 60) // Wait for the main app UI to load fully (increase timeout if needed)
                // Now navigate to the chat via wa.me URL
                ->visit("https://wa.me/{$phoneNumber}")
                ->waitFor('#action-button', 30)  // Wait for the button that redirects to WhatsApp Web
                ->click('#action-button')
                ->pause(5000) // Wait 5 seconds for the chat to open - adjust as needed
                // Focus message input box and type message
                ->waitFor('div[contenteditable="true"]', 30)
                ->click('div[contenteditable="true"]')  // Focus input
                ->type('div[contenteditable="true"]', $message)
                ->keys('div[contenteditable="true"]', ['{enter}'])
                ->pause(2000); // Wait a bit for message to send
        });
    }
}
