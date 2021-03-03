<?php

declare(strict_types=1);

use League\Plates\Engine;
use WileyTechnicalChallenge\SimpleJsonRequest;

require_once __DIR__ . '/../boot.php';

$container = require __DIR__ . '/../config/container.php';

/** @var SimpleJsonRequest $simpleJsonRequest */
$simpleJsonRequest = $container['simple_json_request'];

// GET
$get1 = ($simpleJsonRequest->get('https://httpbin.org/get', ['name' => 'Wiley']));
$get2 = ($simpleJsonRequest->get('https://httpbin.org/get', ['name' => 'Wiley']));

// POST
$post1 = ($simpleJsonRequest->post(
    'https://httpbin.org/post',
    [],
    [
        'name'     => 'John Doe',
        'birthday' => '1984-03-01',
        'document' => '123.321.12-X',
    ]
));
$post2 = ($simpleJsonRequest->post(
    'https://httpbin.org/post',
    [],
    [
        'name'     => 'John Doe',
        'birthday' => '1984-03-01',
        'document' => '123.321.12-X',
    ]
));

// PUT
$put1 = ($simpleJsonRequest->put(
    'https://httpbin.org/put',
    ['id' => 1234],
    [
        'name'     => 'John Doe',
        'birthday' => '1984-03-01',
        'document' => '123.321.12-X',
    ]
));
$put2 = ($simpleJsonRequest->put(
    'https://httpbin.org/put',
    ['id' => 1234],
    [
        'name'     => 'John Doe',
        'birthday' => '1984-03-01',
        'document' => '123.321.12-X',
    ]
));

// DELETE
$delete1 = ($simpleJsonRequest->delete(
    'https://httpbin.org/delete',
    ['id' => 1234],
    ['user' => 88],
));
$delete2 = ($simpleJsonRequest->delete(
    'https://httpbin.org/delete',
    ['id' => 1234],
    ['user' => 88],
));

/**
 * @param array  $rue
 * @param array  $rus
 * @param string $index
 *
 * @return float
 */
function rutime(array $rue, array $rus, string $index): float
{
    return (($rue["ru_$index.tv_sec"] * 1000) + (int)($rue["ru_$index.tv_usec"] / 1000))
        - (($rus["ru_$index.tv_sec"] * 1000) + (int)($rus["ru_$index.tv_usec"] / 1000));
}

$process = "This process used " . rutime(getrusage(), RESOURCE_USAGE, "utime") . " ms for its computations.\n";
$process .= "It spent " . rutime(getrusage(), RESOURCE_USAGE, "stime") . " ms in system calls.\n";
$process .= "Total execution time in seconds: " . (microtime(true) - START_EXECUTION_TIME);

/** @var Engine $templates */
$templates = $container['templates'];

echo $templates->render(
    'app::cache-function',
    [
        'getMode1'        => $get1['mode'],
        'getResponse1'    => $get1['response'],
        'getMode2'        => $get2['mode'],
        'getResponse2'    => $get2['response'],
        'postMode1'       => $post1['mode'],
        'postResponse1'   => $post1['response'],
        'postMode2'       => $post2['mode'],
        'postResponse2'   => $post2['response'],
        'putMode1'        => $put1['mode'],
        'putResponse1'    => $put1['response'],
        'putMode2'        => $put2['mode'],
        'putResponse2'    => $put2['response'],
        'deleteMode1'     => $delete1['mode'],
        'deleteResponse1' => $delete1['response'],
        'deleteMode2'     => $delete2['mode'],
        'deleteResponse2' => $delete2['response'],
        'process'         => $process,
    ]
);
