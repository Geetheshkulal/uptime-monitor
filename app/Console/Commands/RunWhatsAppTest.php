<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunWhatsAppTest extends Command
{
    protected $signature = 'whatsapp:connect';
    protected $description = 'Run WhatsApp connection test';

    public function handle()
    {
        Log::info('Starting WhatsApp Dusk test...');

        try {
            // Check if Dusk is installed
            if (!class_exists(\Laravel\Dusk\Console\DuskCommand::class)) {
                throw new \Exception("Dusk is not installed. Run: composer require --dev laravel/dusk");
            }
            
            // Run the test
            Artisan::call('dusk', [
                    '--filter' => 'WhatsAppLoginTest'
                ]);

            $output = Artisan::output();
            
            Log::info('Dusk test output: ' . $output);

            return $output;
            
        } catch (\Exception $e) {
            Log::error('WhatsApp Dusk test failed: ' . $e->getMessage());
            return false;
        }
    }
}