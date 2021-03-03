<?php

use League\Plates\Engine;
use Pimple\Container;
use WileyTechnicalChallenge\CacheClientFacade;
use WileyTechnicalChallenge\CacheManager;
use WileyTechnicalChallenge\SimpleJsonRequest;

$container = new Container();

$container['cache_client'] = static function () {
    return new CacheClientFacade(
        getenv('REDIS_HOST'),
        (int)getenv('REDIS_PORT'),
        getenv('REDIS_PASS')
    );
};

$container['cache_manager'] = static function ($c) {
    return new CacheManager($c['cache_client']);
};

$container['simple_json_request'] = static function ($c) {
    return new SimpleJsonRequest($c['cache_manager']);
};

$container['templates'] = static function () {
    $templates = new Engine(null, 'phtml');
    $templates->addFolder('app', __DIR__ . '/../templates/app');
    $templates->addFolder('layout', __DIR__ . '/../templates/layout');
    $templates->addFolder('error', __DIR__ . '/../templates/error');

    return $templates;
};

return $container;
