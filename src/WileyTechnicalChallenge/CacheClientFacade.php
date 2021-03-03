<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

use Redis;

/**
 * Class CacheClientFacade
 */
final class CacheClientFacade
{
    /**
     * @var Redis
     */
    private Redis $cacheClient;

    public function __construct(
        string $host = '172.17.0.1',
        int $port = 6379,
        string $pass = ''
    ) {
        $this->cacheClient = new Redis();
        $this->cacheClient->connect($host, $port);
        $this->cacheClient->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        if ($pass) {
            $this->cacheClient->auth($pass);
        }
    }

    /**
     * @param string $key
     * @param string $hashKey
     * @param string $value
     *
     * @return bool
     */
    public function setHash(string $key, string $hashKey, string $value): bool
    {
        return (bool)$this->cacheClient->hSet($key, $hashKey, $value);
    }

    /**
     * @param string $key
     * @param string $hashKey
     *
     * @return bool|array|string
     */
    public function getHash(string $key, string $hashKey = ''): bool|array|string
    {
        if ($hashKey) {
            return $this->cacheClient->hGet($key, $hashKey);
        }

        return $this->cacheClient->hGetAll($key);
    }

    /**
     * @param array|string $key
     * @param string       $hashKey
     *
     * @return bool|int
     */
    public function hasHash(array|string $key, string $hashKey = ''): bool|int
    {
        if ($hashKey) {
            return $this->cacheClient->hExists($key, $hashKey);
        }

        return $this->cacheClient->exists($key);
    }

    /**
     * @param string $key
     * @param int    $ttl
     *
     * @return bool
     */
    public function terminate(string $key, int $ttl): bool
    {
        return $this->cacheClient->expire($key, $ttl);
    }

    /**
     * @param array|int|string $key
     *
     * @return int
     */
    public function delete(array|int|string $key): int
    {
        return $this->cacheClient->del($key);
    }

    /**
     * @return bool
     */
    public function clearAll(): bool
    {
        return $this->cacheClient->flushDB();
    }
}
