<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RunWhatsAppInvoiceBotTest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        try {
            Log::info('Starting WhatsAppInvoiceBotTest via queue job.');

            // Ensure ChromeDriver is running before this
            Artisan::call('dusk', ['--filter' => 'WhatsAppInvoiceBotTest']);

            Log::info('WhatsAppInvoiceBotTest completed successfully.');
            Log::info('Output: ' . Artisan::output());
        } catch (\Exception $e) {
            Log::error('WhatsAppInvoiceBotTest failed in queue job: ' . $e->getMessage());
        }
    }
}
