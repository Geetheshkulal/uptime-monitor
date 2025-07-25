<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Illuminate\Support\Collection;
use Laravel\Dusk\TestCase as BaseTestCase;
use PHPUnit\Framework\Attributes\BeforeClass;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     */
    #[BeforeClass]
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    protected function driver(): RemoteWebDriver
    {
        $userDataDir = storage_path('whatsapp-session');

        $options = (new ChromeOptions)->addArguments([
            '--start-maximized',
            '--disable-search-engine-choice-screen',
            '--disable-smooth-scrolling',
            '--user-data-dir=' . $userDataDir,
            '--profile-directory=Default',
            '--headless'
    ]);

    return RemoteWebDriver::create(
        env('DUSK_DRIVER_URL', 'http://localhost:9515'),
        DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
    );
    
}

        protected function tearDown(): void
        {
                // Do not close the browser
                // parent::tearDown(); ← COMMENT or REMOVE this
        }

}


  