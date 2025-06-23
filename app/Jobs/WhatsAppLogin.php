<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class WhatsAppLogin implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    public  $tries = 1; // Number of attempts before failing the job'
    public $timeout = 200; 
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting WhatsAppLoginTest via queue job.');

            // Ensure ChromeDriver is running before this
            // Artisan::call('dusk', ['test' => 'tests/Browser/WhatsAppLoginTest.php']);
            $process = new Process([
                'php',
                'artisan',
                'dusk',
                'tests/Browser/WhatsAppLoginTest.php'
            ]);
            
            $process->run();

            Log::info('WhatsAppLoginTest completed successfully.');
            // Log::info('Output: ' . Artisan::output());
        } catch (\Exception $e) {
            Log::error('WhatsAppLoginTest failed in queue job: ' . $e->getMessage());
        }
    }
}
