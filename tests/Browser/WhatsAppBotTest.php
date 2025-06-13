<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Log;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\NoSuchElementException;


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

        $url = "https://web.whatsapp.com/send?phone={$phoneNumber}&text=" . urlencode($message);

        $this->browse(function (Browser $browser) use ($url) {
            $browser->visit($url)->pause(8000);
            
                // Wait for pop up appears

                try {
                    $continueButton = $browser->driver->findElement(
                        WebDriverBy::xpath("//*[contains(text(), 'Continue')]")
                    );
            
                    $browser->driver->executeScript("arguments[0].scrollIntoView(true);", [$continueButton]);
                    $continueButton->click();
            
                    Log::info('✅ Clicked "Continue" button using WebDriver XPath.');
                    $browser->pause(5000);
                } catch (NoSuchElementException $e) {
                    Log::warning('⚠️ Continue button not found: ' . $e->getMessage());
                } catch (\Exception $e) {
                    Log::warning('⚠️ Error clicking Continue: ' . $e->getMessage());
                }

                $browser->waitFor('button[aria-label="Send"]', 30)
                        ->click('button[aria-label="Send"]')
                        ->pause(2000);

                // while (true) {
                //     sleep(10); 
                // }
        });

        // Optional: delete payload after test to prevent stale data reuse
        // @unlink($payloadPath);
    }
}

