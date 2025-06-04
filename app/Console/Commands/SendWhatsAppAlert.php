<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Chrome\SupportsChrome;
use Laravel\Dusk\Concerns\ProvidesBrowser;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class SendWhatsAppAlert extends Command
{
    use ProvidesBrowser;

    protected $signature = 'send:whatsapp {phone} {message}';
    protected $description = 'Send a WhatsApp alert using Dusk';

    protected function driver()
    {
        $userDataDir = storage_path('whatsapp-session');

        $options = (new ChromeOptions)->addArguments([
            '--start-maximized',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--user-data-dir=' . $userDataDir,  
            //'--headless=new', 
        ]);

        return RemoteWebDriver::create(
            env('DUSK_DRIVER_URL', 'http://localhost:51847'),
            DesiredCapabilities::chrome()->setCapability(ChromeOptions::CAPABILITY, $options)
        );
    }

    public function handle()
    {
        $phone = $this->argument('phone');
        $message = $this->argument('message');

        $this->browse(function (Browser $browser) use ($phone, $message) {
            $browser->visit('https://web.whatsapp.com')
                ->pause(25000) // Time to scan QR code (initially)
                ->waitFor('div[aria-label="Search input textbox"]', 30)
                ->click('div[aria-label="Search input textbox"]')
                ->keys('div[aria-label="Search input textbox"]', $phone, '{enter}')
                ->pause(3000)
                ->waitFor('div[role="textbox"][aria-label="Type a message"]', 10)
                ->click('div[role="textbox"][aria-label="Type a message"]')
                ->keys('div[role="textbox"][aria-label="Type a message"]', $message, '{enter}')
                ->pause(2000);
        });
    }
}
