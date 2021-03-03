<?php

declare(strict_types=1);

use League\Plates\Engine;

require_once __DIR__ . '/../boot.php';

$container = require __DIR__ . '/../config/container.php';

/** @var Engine $templates */
$templates = $container['templates'];

echo $templates->render('app::date-format');
