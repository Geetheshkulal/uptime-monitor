<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

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

