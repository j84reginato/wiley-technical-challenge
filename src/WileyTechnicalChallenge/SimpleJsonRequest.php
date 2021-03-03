<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

use JsonException;

/**
 * Class SimpleJsonRequest
 */
final class SimpleJsonRequest
{
    private const OBJECT_TYPE = 'request';

    /**
     * @var CacheManagerInterface
     */
    private CacheManagerInterface $cacheManager;

    public function __construct(CacheManagerInterface $cacheManager)
    {
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param string $url
     * @param array  $parameters
     *
     * @return array
     */
    public function get(string $url, array $parameters = []): array
    {
        return $this->process('GET', $url, $parameters);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    public function post(string $url, array $parameters = [], array $data = []): array
    {
        return $this->process('POST', $url, $parameters, $data);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    public function put(string $url, array $parameters = [], array $data = []): array
    {
        return $this->process('PUT', $url, $parameters, $data);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    public function patch(string $url, array $parameters = [], array $data = []): array
    {
        return $this->process('PATCH', $url, $parameters, $data);
    }

    /**
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    public function delete(string $url, array $parameters = [], array $data = []): array
    {
        return $this->process('DELETE', $url, $parameters, $data);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    private function process(string $method, string $url, array $parameters = [], array $data = []): array
    {
        $response = $this->hasCache($method, $url, $parameters, $data);

        if ($response) {
            return $response;
        }

        return $this->makeRequest($method, $url, $parameters, $data);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    private function hasCache(string $method, string $url, array $parameters = [], array $data = []): array
    {
        $hashKey = self::OBJECT_TYPE . ":$method:{$this->cacheManager->getKeyByParams($url, $parameters, $data)}";

        if (
            $this->cacheManager->hasHash($hashKey)
            && $this->cacheManager->hasHash($hashKey, 'response')
            && $this->cacheManager->retrieve($hashKey, 'url') === $url
            && $this->compare($hashKey, 'parameters', $parameters)
            && $this->compare($hashKey, 'data', $data)
        ) {
            return [
                'response' => $this->cacheManager->retrieve($hashKey, 'response'),
                'mode'     => 'REQUEST CACHED',
            ];
        }

        return [];
    }

    /**
     * @param string $hashKey
     * @param string $field
     * @param array  $paramValue
     *
     * @return bool
     */
    private function compare(string $hashKey, string $field, array $paramValue): bool
    {
        try {
            $cachedValue = json_decode($this->cacheManager->retrieve($hashKey, $field), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            echo $e->getMessage();
        }

        return ($cachedValue ?? []) === $paramValue;
    }

    /**
     * @param string $method
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return array
     */
    private function makeRequest(string $method, string $url, array $parameters = [], array $data = []): array
    {
        try {
            $opts = [
                'http' => [
                    'method'  => $method,
                    'header'  => 'Content-type: application/json',
                    'content' => $data ? json_encode($data, JSON_THROW_ON_ERROR) : null,
                ],
            ];

            $response = $this->getUrlContents(
                $url . ($parameters ? '?' . http_build_query($parameters) : ''),
                $opts
            );

            $hashKey = self::OBJECT_TYPE . ":$method:{$this->cacheManager->getKeyByParams($url, $parameters, $data)}";

            $this->cacheManager->store($hashKey, 'url', $url);
            $this->cacheManager->store($hashKey, 'parameters', json_encode($parameters ?? [], JSON_THROW_ON_ERROR));
            $this->cacheManager->store($hashKey, 'data', json_encode($data ?? [], JSON_THROW_ON_ERROR));
            $this->cacheManager->store($hashKey, 'response', $response ?? '');

            $this->cacheManager->terminate($hashKey, (int)getenv('CACHE_LIFE_TIME'));
        } catch (JsonException $e) {
            echo $e->getMessage();
        }

        return [
            'response' => $response ?? '',
            'mode'     => 'NO CACHED',
        ];
    }

    /**
     * @param string $url
     * @param array  $options
     *
     * @return string
     */
    private function getUrlContents(string $url, array $options): string
    {
        $content = (string)file_get_contents($url, false, stream_context_create($options));

        return mb_convert_encoding(
            $content,
            'UTF-8',
            mb_detect_encoding($content, 'UTF-8, ISO-8859-1', true)
        );
    }
}
