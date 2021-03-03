<?php

declare(strict_types=1);

use Dotenv\Dotenv;

date_default_timezone_set('Etc/GMT+3');

define('RESOURCE_USAGE', getrusage());
define('START_EXECUTION_TIME', microtime(true));
define('APP_ROOT', dirname(__DIR__));

if (PHP_SAPI === 'cli-server' && $_SERVER['SCRIPT_FILENAME'] !== __FILE__) {
    return false;
}

chdir(dirname(__DIR__));

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

if (getenv('APPLICATION_ENV') !== 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
