<?php

namespace Tests\Browser;

use App\Models\Monitors;
use App\Models\Template;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\Log;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\NoSuchElementException;


class WhatsAppBotTest extends DuskTestCase
{
    private function replaceTemplateVariables($monitor, string $content): string
    {
        $incident = $monitor->latestIncident();

        // Replace placeholders
        $replacedContent = str_replace(
            [
                '{{user_name}}',
                '{{monitor_name}}',
                '{{down_timestamp}}',
                '{{up_timestamp}}',
                '{{monitor_type}}',
                '{{downtime_duration}}',
            ],
            [
                optional($monitor->user)->name ?? 'N/A',
                $monitor->name,
                optional($incident?->start_timestamp)?->format('Y-m-d H:i:s') ?? 'N/A',
                optional($incident?->end_timestamp)?->format('Y-m-d H:i:s') ?? 'N/A',
                $monitor->type,
                $incident?->end_timestamp
                    ? $incident->end_timestamp->diffInMinutes($incident->start_timestamp) . ' minutes'
                    : 'N/A',
            ],
            $content
        );

        // Clean BOM and zero-width characters
        $cleaned = preg_replace('/\x{FEFF}/u', '', $replacedContent);
        return str_replace("\xEF\xBB\xBF", '', $cleaned);
    }

    private function convertHtmlToWhatsappText($html)
    {
        $text = $html;

        // Bold and Italic replacements
        $text = str_replace(['<strong>', '</strong>', '<b>', '</b>'], '*', $text);
        $text = str_replace(['<em>', '</em>', '<i>', '</i>'], '_', $text);

        // Line breaks and paragraphs
        $text = preg_replace('/<br\s*\/?>/i', "\n", $text);
        $text = preg_replace('/<\/?p[^>]*>/i', "\n", $text);

        // Handle ordered list (must run before li is stripped)
        $text = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/is', function ($matches) {
            $items = [];
            preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $matches[1], $liMatches);
            foreach ($liMatches[1] as $index => $item) {
                $items[] = ($index + 1) . '. ' . strip_tags($item);
            }
            return implode("\n", $items) . "\n";
        }, $text);

        // Handle unordered list
        $text = preg_replace('/<ul[^>]*>/', '', $text);
        $text = preg_replace('/<\/ul>/', '', $text);
        $text = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "• $1\n", $text);

        // Decode HTML entities and remove leftover tags
        $text = html_entity_decode(strip_tags($text));

        // Remove extra newlines
        $text = preg_replace("/\n{2,}/", "\n", $text);

        return trim($text);
    }

    public function testSendWhatsAppMessage()
    {
        $payloadPath = storage_path('app/whatsapp-payload.json');

        if (!file_exists($payloadPath)) {
            $this->fail('❌ WhatsApp payload JSON not found.');
            return;
        }

        $payload = json_decode(file_get_contents($payloadPath), true);

        $monitor = Monitors::find($payload['monitor_id']);

        $rawPhone = $monitor->user->phone;
        $phoneNumber = preg_match('/^\d{10}$/', $rawPhone) ? str_replace('+','',$monitor->user->country_code). $rawPhone : $rawPhone;
      
        $template = Template::where('template_name', $payload['template_used'])->first();

        echo "Using template: {$template->template_name}\n";

        $templateContent = $template->content;
        

        $message = <<<TEXT
                {$this->convertHtmlToWhatsappText($this->replaceTemplateVariables($monitor, $templateContent))}
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

