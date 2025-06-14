<?php
namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Tests\DuskTestCase;
use Illuminate\Support\Facades\File;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Exception\NoSuchElementException;



class WhatsAppLoginTest extends DuskTestCase
{
    public function testWhatsappSessionLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('https://web.whatsapp.com');


            Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
            Storage::put('whatsapp/status.txt', 'pending');
        
            sleep(15);
        
            $qrBase64 = $browser->script("
                let canvas = document.querySelector('canvas');
                return canvas ? canvas.toDataURL() : null;
            ")[0];
        
            if ($qrBase64) {
                Storage::put('whatsapp/qr.txt', $qrBase64);
                Log::info('[WHATSAPP SESSION] QR code saved.');
            } else {
                Storage::put('whatsapp/status.txt', 'pending');
                Storage::delete('whatsapp/qr.txt');

               
                //File::deleteDirectory(storage_path('whatsapp-session'));
                Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
            }
        

            $qrScanned = false;

            try {
                $qrScanned = $browser->waitUsing(30, 2, function () use ($browser) {
                    return $browser->script("return document.querySelector('canvas') === null;")[0];
                });
            } catch (TimeoutException $e) {
                // QR was never scanned
                Storage::put('whatsapp/status.txt', 'disconnected');
                Storage::delete('whatsapp/qr.txt');

                //File::deleteDirectory(storage_path('whatsapp-session'));
                Log::warning('[WHATSAPP SESSION] QR not scanned in time.');
                return;
            }
        
            // QR was scanned, now waiting for login
            if($qrScanned){
                Storage::put('whatsapp/status.txt', 'loading');
                Log::info('[WHATSAPP SESSION] QR scanned. Waiting for login...');
            }
        
            try {
                $isLoggedIn = $browser->waitUsing(120, 5, function () use ($browser) {
                    return $browser->script("
                        return document.querySelector('[aria-label=\"Chat list\"]') !== null;
                    ")[0];
                });
            } catch (TimeoutException $e) {
                Storage::put('whatsapp/status.txt', 'disconnected');
                Storage::delete('whatsapp/qr.txt');
                File::deleteDirectory(storage_path('whatsapp-session'));
                Log::warning('[WHATSAPP SESSION] Login failed after QR scanned.');
                return;
            }
        
            Storage::put('whatsapp/status.txt', 'connected');
            Log::info('[WHATSAPP SESSION] WhatsApp login successful!');

            // to remove continue button

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

            
            $browser->pause(3000)->script("
                let profileBtn = document.querySelector('[aria-label=\"Profile\"]');
                if(profileBtn) profileBtn.click();
            ");
            Log::info('👤 Clicked Profile button');

            $browser->pause(3000);

            $name = $browser->script("
                let nameElem = document.querySelector('div._alcd span._ao3e.selectable-text.copyable-text span');
                return nameElem ? nameElem.textContent.trim() : null;
            ");

            if (!empty($name[0])) {
                $userName = $name[0];
                Log::info('✅ Retrieved WhatsApp user name: ' . $userName);
                Storage::put('whatsapp/user_name.txt', $userName);
            } else {
                Log::warning('⚠️ Could not extract WhatsApp user name.');
            }

            $browser->pause(2000)->script("
            const profileBtn = document.querySelector('button[aria-label=\"View group profile photo\"]');
            if (profileBtn) profileBtn.click();
        ");
         Log::info('✅ Clicked profile photo button to open dropdown');
        
            $browser->pause(3000)->script("
                let viewPhoto = [...document.querySelectorAll('li._aj-r')].find(el => el.textContent.includes('View photo'));
                if(viewPhoto) viewPhoto.click();
            ");
            Log::info('👁️ Clicked View photo');

            // $browser->pause(3000)->screenshot('whatsapp/profile_photo');
            $browser->pause(3000)->screenshot('temp_screenshot');

            // Move the screenshot to storage
            $filePath = base_path('tests/Browser/screenshots/temp_screenshot.png');
            $contents = file_get_contents($filePath);
            Storage::put('whatsapp/profile_photo.png', $contents);

            // (Optional) delete original file
            unlink($filePath);

        });

    }
}

// class WhatsAppLoginTest extends DuskTestCase
// {
//     public function testWhatsappSessionLogin()
//     {
//         $this->browse(function (Browser $browser) {
//             $browser->visit('https://web.whatsapp.com');

//             Log::info('[WHATSAPP SESSION] Opened WhatsApp Web');
//             Storage::put('whatsapp/status.txt', 'pending');

//             sleep(15); 

//             $qrBase64 = $browser->script("
//                 let canvas = document.querySelector('canvas');
//                 return canvas ? canvas.toDataURL() : null;
//             ")[0];

//             if ($qrBase64) {
//                 Storage::put('whatsapp/qr.txt', $qrBase64);
//                 Log::info('[WHATSAPP SESSION] QR saved');
//             }else{
//                 Log::warning('[WHATSAPP SESSION] QR code not found');
//             }

//             $browser->waitUntilMissing('canvas', 30);
//             Storage::put('whatsapp/status.txt', 'loading');

//             $browser->waitUsing(120, 5, function () use ($browser) {
//                 return $browser->script("
//                     return document.querySelector('[aria-label=\"Chat list\"]') !== null;
//                 ")[0];
//             });

//             $isLoggedIn = $browser->script("
//                 return document.querySelector('[aria-label=\"Chat list\"]') !== null;
//             ")[0];

//             if ($isLoggedIn) {
//                 Storage::put('whatsapp/status.txt', 'connected');
//                 Log::info('[WHATSAPP SESSION] WhatsApp login successful!');
//             } else {
//                 Storage::put('whatsapp/status.txt', 'pending');
//                 Log::warning('[WHATSAPP SESSION] Login fallback check failed. Still pending...');
//             }

           
//             sleep(30);
//         });
//     }
// }
