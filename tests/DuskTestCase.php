<?php

namespace Tests;

use Laravel\Dusk\TestCase as BaseTestCase;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

use Facebook\WebDriver\WebDriverBy;

use App\User;

abstract class DuskTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        static::startChromeDriver();
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $driver_server = env('WEBDRIVER_HOST', 'localhost');
        $driver_port = env('WEBDRIVER_PORT', '9515');

        return RemoteWebDriver::create(
            'http://'. $driver_server. ':'. $driver_port,
            DesiredCapabilities::chrome()
        );
    }

    /**
     * Laravel Dusk throws an exception if this function does not exist.
     * Tests work without anything being returned. It may be for returning a default user to login as.
     *
     * @return void
     */
    protected function user()
    {
        return;
    }
}
