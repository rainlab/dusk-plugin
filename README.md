# Dusk Plugin

Browser testing for October CMS, powered by Laravel Dusk.

## Installation Instructions

To install with Composer, run from your project root

```bash
composer require rainlab/dusk-plugin
```

If using the latest version of Chrome, install the latest version of ChromeDriver for your OS.

```bash
php artisan dusk:chrome-driver
```

Otherwise, check the version of the Chrome browser you have installed, and install a given version of ChromeDriver for your OS.

```bash
php artisan dusk:chrome-driver 86
```

## Defining Tests

To make your first test, create a new class inside the **tests/browser** folder. The following will authenticate to the backend panel and sign out again.

```php
// File: plugins/october/test/tests/browser/AuthenticationTest.php
//
class AuthenticationTest extends BrowserTestCase
{
    public function testAuthentication()
    {
        $this->browse(function($browser) {
            $browser
                ->visit('/admin')
                ->waitForLocation('/admin/backend/auth/signin')
                ->assertTitleContains('Administration Area |')
                ->type('login', env('DUSK_ADMIN_USER', 'admin'))
                ->type('password', env('DUSK_ADMIN_PASS', 'admin'))
                ->check('remember')
                ->press('Login');

            $browser
                ->waitForLocation('/admin')
                ->assertTitleContains('Dashboard |')
                ->click('#layout-mainmenu .mainmenu-account > a')
                ->clickLink('Sign Out');

            $browser
                ->waitForLocation('/admin/backend/auth/signin')
                ->assertTitleContains('Administration Area |');
        });
    }
}
```

## Creating Environment File

The `.env.dusk` environment file can be used for Dusk specific configuration. It is advisable to include the application URL to test in this file.

```bash
APP_URL=http://mylocalsite.dev/
```

## Running Tests

Use the `test:dusk` artisan command to run the dusk tests for a plugin code (first argument).

```bash
php artisan test:dusk <PLUGIN CODE>
```

The following runs tests for the October.Test plugin.

```bash
php artisan test:dusk october.test
```

Use the `--browse` to enable interactive mode.

```bash
php artisan test:dusk october.test --browse
```

Use the `--filter` option to run a single test where the value is the test class name.

```
php artisan test:dusk october.test --filter=PeopleTest
```

## End to End Example

Follow these instructions to get a test up and running.

1. Install the [latest version of Chrome](https://www.google.com.au/chrome/) browser

1. Install a [fresh copy of October CMS](https://docs.octobercms.com/)

1. Install this plugin `composer require rainlab/dusk-plugin`

1. Install latest chrome driver `php artisan dusk:chrome-driver`

1. Install [Test plugin](https://github.com/octobercms/test-plugin) `php artisan plugin:install October.Test --from=https://github.com/octobercms/test-plugin`

1. Create a file `.env.dusk` and include `APP_URL=http://yourlocaldev.tld` inside

1. Run tests `php artisan test:dusk october.test --browse`

### See Also

- [Laravel Dusk](https://laravel.com/docs/9.x/dusk)

### License

This plugin is an official extension of the October CMS platform and is free to use if you have a platform license. See [EULA license](https://octobercms.com/eula) for more details.
