<?php

/**
 * Dates ranges - Test Task to complete for Senior Backend Engineer (Remote) role at Cloudbeds
 *
 * @package  Dates ranges
 * @author   Petr Saganov <saganoff@gmail.com>
 */

use DateRange\Config\AppRoutes;
use DateRange\Core\Application;

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/
require __DIR__.'/../vendor/autoload.php';

$app = new Application(new AppRoutes());
$app->run();
