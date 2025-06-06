<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckWhatsAppConnection extends Command
{
    protected $signature = 'whatsapp:check-status';
    protected $description = 'Check WhatsApp Web login status using Dusk';

    public static bool $isConnected = false;

    public function handle()
    {
        (new class extends DuskTestCase {
            public function check(): bool
            {
                $status = false;
                $this->browse(function (Browser $browser) use (&$status) {
                    $browser->visit('https://web.whatsapp.com')->pause(5000);
                    $status = $browser->script("
                        return !!document.querySelector('div[role=\"textbox\"]');
                    ")[0];
                });
                return $status;
            }
        })->check();

        static::$isConnected = true; // Optional: set to true based on above if needed
    }
}