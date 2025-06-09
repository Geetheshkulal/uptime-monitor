<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Dusk\Browser;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;


class WhatsAppSessionRunner extends Command
{
    protected $signature = 'whatsapp:run-session';
    protected $description = 'Keep WhatsApp Web session alive in background';

    public function handle()
    {
        $process = new Process([
            base_path('vendor/laravel/dusk/bin/chromedriver.exe'),
            '--port=9515'
        ]);
        $process->start();
        sleep(2); // Wait for ChromeDriver to be ready

        $userDataDir = storage_path('whatsapp-session');
        $options = (new ChromeOptions)->addArguments([
            '--disable-gpu',
           // '--headless=new', // or remove headless to view actual browser
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--window-size=1920,1080',
            '--user-data-dir=' . $userDataDir,
        ]);

        $driver = RemoteWebDriver::create(
            'http://localhost:9515', // chromedriver must be running
            DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
        );

        $browser = new Browser($driver);

        try {
            $browser->visit('https://web.whatsapp.com');
            Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
            Storage::put('whatsapp/status.txt', 'pending');

            // Wait for QR
            sleep(15);

            $qrBase64 = $browser->script("
                let canvas = document.querySelector('canvas');
                return canvas ? canvas.toDataURL() : null;
            ")[0];

            if ($qrBase64) {
                Storage::put('whatsapp/qr.txt', $qrBase64);
                Log::info('[WHATSAPP SESSION] QR saved');
            }

            // Wait until QR disappears (i.e. login)
            $browser->waitUsing(120, 5, function () use ($browser) {
                return $browser->script("
                    return document.querySelector('[aria-label=\"Chat list\"]') !== null;
                ")[0];
            });
            
            // Double-check fallback
            $isLoggedIn = $browser->script("
                return document.querySelector('[aria-label=\"Chat list\"]') !== null;
            ")[0];

            if ($isLoggedIn) {
                Storage::put('whatsapp/status.txt', 'connected');
                Log::info('[WHATSAPP SESSION] WhatsApp login successful!');
            } else {
                Storage::put('whatsapp/status.txt', 'pending');
                Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
            }

            // Keep session alive
            while (true) {
                sleep(30);
                Log::info('[WHATSAPP SESSION] Still alive...');
            }
        } catch (\Exception $e) {
            Storage::put('whatsapp/status.txt', 'error');
            Log::error('[WHATSAPP SESSION ERROR] ' . $e->getMessage());
        } finally {
            // Optional: don't quit
            // $browser->quit();
        }
    }
}
