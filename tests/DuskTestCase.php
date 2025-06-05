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

    /**
     * Create the RemoteWebDriver instance.
     */
protected function driver()
{
    $options = (new ChromeOptions)->addArguments([
        '--disable-gpu',
       //'--headless', // Optional: remove this if you want visible Chrome
        '--window-size=1920,1080',
        '--user-data-dir=' . storage_path('chrome-profile'), // <-- this is what fixes the issue
        '--disable-dev-shm-usage',
        '--no-sandbox'
    ]);

    return RemoteWebDriver::create(
        'http://localhost:9515', // ChromeDriver must be running
        DesiredCapabilities::chrome()->setCapability(
            ChromeOptions::CAPABILITY, $options
        )
    );
}



}
