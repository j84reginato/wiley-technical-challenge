<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

use PHPUnit\Framework\TestCase;

/**
 * Class CacheClientFacadeTest
 */
class CacheClientFacadeTest extends TestCase
{
    /**
     * @var CacheClientFacade
     */
    protected CacheClientFacade $cacheClient;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->cacheClient = new CacheClientFacade(
            getenv('REDIS_HOST'),
            (int)getenv('REDIS_PORT')
        );
    }

    /**
     * @return void
     */
    public function testSetHash(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        self::assertEquals(true, $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1'));
        self::assertEquals(false, $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1'));
    }

    /**
     * @return void
     */
    public function testGetHash(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1');

        self::assertEquals('value1', $this->cacheClient->getHash('phpunit:test:1', 'key1'));
        self::assertEquals(
            [
                'key1' => 'value1',
            ],
            $this->cacheClient->getHash('phpunit:test:1')
        );
    }

    /**
     * @return void
     */
    public function testHasHash(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1');
        $this->cacheClient->setHash('phpunit:test:2', 'key2', 'value2');

        self::assertEquals(1, $this->cacheClient->hasHash('phpunit:test:1'));
        self::assertTrue($this->cacheClient->hasHash('phpunit:test:1', 'key1'));
        self::assertEquals(2, $this->cacheClient->hasHash(['phpunit:test:1', 'phpunit:test:2']));

        $this->cacheClient->delete('phpunit:test:2');
        self::assertEquals(0, $this->cacheClient->hasHash('phpunit:test:2'));
    }

    /**
     * @return void
     */
    public function testTerminate(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1');
        self::assertTrue($this->cacheClient->terminate('phpunit:test:1', 1));

        self::assertEquals(1, $this->cacheClient->hasHash('phpunit:test:1'));
        sleep(2);
        self::assertEquals(0, $this->cacheClient->hasHash('phpunit:test:1'));
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheClient->setHash('phpunit:test:1', 'key1', 'value1');
        self::assertEquals(1, $this->cacheClient->delete('phpunit:test:1'));
        self::assertEquals(0, $this->cacheClient->hasHash('phpunit:test:1'));
    }

    /**
     * @return void
     */
    public function testClearAll(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        self::assertTrue($this->cacheClient->clearAll());
    }
}
