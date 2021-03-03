<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

/**
 * Interface CacheManagerInterface
 */
interface CacheManagerInterface
{
    /**
     * @param string $key
     * @param string $field
     * @param string $value
     *
     * @return bool
     */
    public function store(string $key, string $field, string $value): bool;

    /**
     * @param string $key
     * @param string $hashKey
     *
     * @return bool|array|string
     */
    public function retrieve(string $key, string $hashKey = ''): bool|array|string;

    /**
     * @param array|string $key
     * @param string       $hashKey
     *
     * @return bool
     */
    public function hasHash(array|string $key, string $hashKey = ''): bool;

    /**
     * @param string $key
     * @param int    $ttl
     *
     * @return bool
     */
    public function terminate(string $key, int $ttl): bool;

    /**
     * @param array|int|string $key
     *
     * @return int
     */
    public function delete(array|int|string $key): int;

    /**
     * @return bool
     */
    public function clearAll(): bool;
}
