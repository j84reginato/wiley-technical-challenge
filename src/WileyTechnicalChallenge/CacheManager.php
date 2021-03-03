<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

/**
 * Class CacheManager
 */
final class CacheManager extends CacheManagerDecorator implements CacheManagerInterface
{
    /**
     * Expiration cache time (default 5 min)
     */
    private const LIFE_TIME = 300;

    /**
     * @var CacheClientFacade
     */
    private CacheClientFacade $cacheClient;

    public function __construct(CacheClientFacade $cacheClient)
    {
        $this->cacheClient = $cacheClient;
    }

    /**
     * @param string $key
     * @param string $field
     * @param string  $value
     *
     * @return bool
     */
    public function store(string $key, string $field, string $value): bool
    {
        return $this->cacheClient->setHash($key, $field, $value);
    }

    /**
     * @param string      $key
     * @param string $hashKey
     *
     * @return bool|array|string
     */
    public function retrieve(string $key, string $hashKey = ''): bool|array|string
    {
        if (!getenv('CACHE_ENABLED') || !$this->hasHash($key)) {
            return '';
        }

        return $this->cacheClient->getHash($key, $hashKey);
    }

    /**
     * @param array|string $key
     * @param string       $hashKey
     *
     * @return bool
     */
    public function hasHash(array|string $key, string $hashKey = ''): bool
    {
        return (bool)$this->cacheClient->hasHash($key, $hashKey);
    }

    /**
     * @param string $key
     * @param int    $ttl
     *
     * @return bool
     */
    public function terminate(string $key, int $ttl = self::LIFE_TIME): bool
    {
        return $this->cacheClient->terminate($key, $ttl);
    }

    /**
     * @param array|int|string $key
     *
     * @return int
     */
    public function delete(array|int|string $key): int
    {
        return $this->cacheClient->delete($key);
    }

    /**
     * @return bool
     */
    public function clearAll(): bool
    {
        return $this->cacheClient->clearAll();
    }

    /**
     * @param mixed ...$params
     *
     * @return string
     */
    public function getKeyByParams(...$params): string
    {
        return sha1(serialize($params));
    }
}
