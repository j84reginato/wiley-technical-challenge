<?php

declare(strict_types=1);

use Dotenv\Dotenv;
use WileyTechnicalChallenge\SimpleJsonRequest;

date_default_timezone_set('Etc/GMT+3');

require_once __DIR__ . '/../vendor/autoload.php';

copy(__DIR__ . '/../environment/test.env', __DIR__ . '/.env');

$dotenv = Dotenv::createUnsafeImmutable(__DIR__ . '/../');
$dotenv->load();

if (getenv('APPLICATION_ENV') !== 'production') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

$container = require __DIR__ . '/../config/container.php';

/** @var SimpleJsonRequest $simpleJsonRequest */
$simpleJsonRequest = $container['simple_json_request'];
