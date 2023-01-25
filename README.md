# Dusk Plugin

Browser testing for October CMS, powered by Laravel Dusk.

## Installation Instructions

To install with Composer, run from your project root

```bash
composer require rainlab/dusk-plugin
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

### License

This plugin is an official extension of the October CMS platform and is free to use if you have a platform license. See [EULA license](https://octobercms.com/eula) for more details.
