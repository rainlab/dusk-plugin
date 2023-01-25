<?php

// Autoload October
require_once __DIR__ . '/../../../../modules/system/tests/bootstrap.php';

// Dusk tests
if (class_exists(\Laravel\Dusk\TestCase::class)) {
    require 'BrowserTestCase.php';
}
