<?php

// Autoload October
require_once __DIR__ . '/../../../../modules/system/tests/bootstrap.php';

// Dusk tests
require 'BrowserTestCase.php';

// Macros
\Laravel\Dusk\Browser::macro('ajaxRequest', function($element = null, $handler = null) {
    $element = $element ? "'".$element."'" : '';
    $handler = $handler ? "'".$handler."'" : '';
    $this->script("$($element).request($handler)");
    return $this;
});
