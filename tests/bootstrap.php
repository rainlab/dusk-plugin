<?php

// Autoload October
require_once __DIR__ . '/../../../../modules/system/tests/bootstrap.php';

// Dusk tests
require 'BrowserTestCase.php';

// Macros
\Laravel\Dusk\Browser::macro('ajaxRequest', function($element = null) {
    $this->script("$('$element').request()");
    return $this;
});
