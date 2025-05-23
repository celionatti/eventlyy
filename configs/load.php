<?php

declare(strict_types=1);

/**
 * Framework: PhpStrike
 * Author: Celio Natti
 * version: 1.0.0
 * Year: 2023
 *
 * Description: This file is for global constants
 */


if (!file_exists(__DIR__ . '/constants.php')) {
    die("Constants File Not Found!");
}

require __DIR__ . '/constants.php';

if (!defined('URL_ROOT')) {
    define('URL_ROOT', $_ENV["URL_ROOT"]);
}

if (!defined('ENABLE_BLADE')) {
    define('ENABLE_BLADE', false);
}

if (!defined('ENABLE_TWIG')) {
    define('ENABLE_TWIG', false);
}

if (!defined('CONFIG_ROOT')) {
    define('CONFIG_ROOT', "configs/config.json");
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', false);
}

if (!defined('BOLT_DATABASE')) {
    define('BOLT_DATABASE', "bolt_database");
}

if (!defined('DB_NAME')) {
    define('DB_NAME', $_ENV["DB_NAME"]);
}

if (!defined('DB_USERNAME')) {
    define('DB_USERNAME', $_ENV["DB_USERNAME"]);
}

if (!defined('DB_PASSWORD')) {
    define('DB_PASSWORD', $_ENV["DB_PASSWORD"]);
}

if (!defined('DB_DRIVERS')) {
    define('DB_DRIVERS', $_ENV["DB_DRIVERS"]);
}

if (!defined('DB_HOST')) {
    define('DB_HOST', $_ENV["DB_HOST"]);
}

if (!defined('REMEMBER_ME_NAME')) {
    define('REMEMBER_ME_NAME', "eventlyy_remember_me");
}
