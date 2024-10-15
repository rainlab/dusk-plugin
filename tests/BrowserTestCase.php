<?php

use Laravel\Dusk\Browser;
use Illuminate\Support\Collection;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Laravel\Dusk\TestCase as DuskTestCase;

abstract class BrowserTestCase extends DuskTestCase
{
    /**
     * Register the base URL with Dusk.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Browser::$baseUrl = $this->baseUrl();

        Browser::$storeScreenshotsAt = $this->pluginPathCreated('tests/browser/screenshots');

        Browser::$storeConsoleLogAt = $this->pluginPathCreated('tests/browser/console');

        Browser::$storeSourceAt = $this->pluginPathCreated('tests/browser/source');
    }

    /**
     * createApplication
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../bootstrap/app.php';

        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $app['cache']->setDefaultDriver('array');
        $app->setLocale('en');

        return $app;
    }

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     */
    public static function prepare(): void
    {
        if (! static::runningInSail()) {
            static::startChromeDriver(['--port=9515']);
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     */
    protected function driver(): RemoteWebDriver
    {
        $options = (new ChromeOptions)->addArguments(collect([
            $this->shouldStartMaximized() ? '--start-maximized' : '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
            ]);
        })->when($this->hasDevShmUsageDisabled(), function (Collection $items) {
            return $items->merge([
                '--disable-dev-shm-usage',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://localhost:9515',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     */
    protected function hasHeadlessDisabled(): bool
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    /**
     * Determine if we should disable dev SHM usage in chromedriver
     */
    protected function hasDevShmUsageDisabled(): bool
    {
        return isset($_SERVER['DUSK_DEV_SHM_USAGE_DISABLED']) ||
               isset($_ENV['DUSK_DEV_SHM_USAGE_DISABLED']);
    }

    /**
     * Determine if the browser window should start maximized.
     */
    protected function shouldStartMaximized(): bool
    {
        return isset($_SERVER['DUSK_START_MAXIMIZED']) ||
               isset($_ENV['DUSK_START_MAXIMIZED']);
    }

    /**
     * pluginPathCreated
     */
    protected function pluginPathCreated($path = '')
    {
        $path = $this->pluginPath($path);

        if (!is_dir($path)) {
            @mkdir($path, 0777, true);
        }

        return $path;
    }

    /**
     * pluginPath
     */
    protected function pluginPath($path = '')
    {
        return plugins_path(str_replace('.', '/', $this->guessPluginCodeFromTest()).'/'.$path);
    }

    /**
     * guessPluginCodeFromTest locates the plugin code based on the test file location.
     * @return string|bool
     */
    protected function guessPluginCodeFromTest()
    {
        $reflect = new ReflectionClass($this);
        $path = $reflect->getFilename();
        $basePath = $this->app->pluginsPath();

        $result = false;

        if (strpos($path, $basePath) === 0) {
            $result = ltrim(str_replace('\\', '/', substr($path, strlen($basePath))), '/');
            $result = implode('.', array_slice(explode('/', $result), 0, 2));
        }

        return $result;
    }
}
