<?php

declare(strict_types=1);


use Dotenv\Dotenv;
use celionatti\Bolt\Bolt;


/**
 * =======================================
 * Index Page ============================
 * =======================================
 */

// require __DIR__ . '/../vendor/autoload.php';
$autoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($autoload)) {
    die('Composer autoload file not found. Run composer install.');
}
require $autoload;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$bolt = new Bolt();

require $bolt->pathResolver->router_path("web.php");
require $bolt->pathResolver->router_path("api.php");

$bolt->run();
