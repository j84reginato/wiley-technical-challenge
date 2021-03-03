<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

use PHPUnit\Framework\TestCase;

/**
 * Class CacheManagerTest
 */
class CacheManagerTest extends TestCase
{
    /**
     * @var CacheManager
     */
    protected CacheManager $cacheManager;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->cacheManager = new CacheManager(
            new CacheClientFacade(
                getenv('REDIS_HOST'),
                (int)getenv('REDIS_PORT')
            )
        );
    }

    /**
     * @return void
     */
    public function testStore(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheManager->delete('phpunit:test:1');

        self::assertIsBool($this->cacheManager->store('phpunit:test:1', 'key1', 'value1'));
        self::assertIsBool($this->cacheManager->store('phpunit:test:1', 'key2', 'value2'));
    }

    /**
     * @return void
     */
    public function testRetrieve(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheManager->store('phpunit:test:1', 'key1', 'value1');
        $this->cacheManager->store('phpunit:test:1', 'key2', 'value2');

        self::assertNotNull($this->cacheManager->retrieve('phpunit:test:1', 'key1'));
        self::assertEquals('value1', $this->cacheManager->retrieve('phpunit:test:1', 'key1'));

        self::assertNotNull($this->cacheManager->retrieve('phpunit:test:1', 'key2'));
        self::assertEquals('value2', $this->cacheManager->retrieve('phpunit:test:1', 'key2'));

        self::assertNotNull($this->cacheManager->retrieve('phpunit:test:1'));
        self::assertIsArray($this->cacheManager->retrieve('phpunit:test:1'));
        self::assertEquals(
            [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
            $this->cacheManager->retrieve('phpunit:test:1')
        );

        $this->cacheManager->delete('phpunit:test:1');
        self::assertEmpty($this->cacheManager->retrieve('phpunit:test:1'));
    }

    /**
     * @return void
     */
    public function testHasHash(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheManager->store('phpunit:test:1', 'key1', 'value1');
        $this->cacheManager->store('phpunit:test:1', 'key2', 'value2');

        self::assertTrue($this->cacheManager->hasHash('phpunit:test:1'));
        self::assertTrue($this->cacheManager->hasHash('phpunit:test:1', 'key1'));
        self::assertTrue($this->cacheManager->hasHash('phpunit:test:1', 'key2'));
    }

    /**
     * @return void
     */
    public function testTerminate(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheManager->store('phpunit:test:1', 'key1', 'value1');
        self::assertTrue($this->cacheManager->terminate('phpunit:test:1', 1));

        self::assertTrue($this->cacheManager->hasHash('phpunit:test:1'));
        sleep(2);
        self::assertFalse($this->cacheManager->hasHash('phpunit:test:1'));
    }

    /**
     * @return void
     */
    public function testDelete(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        $this->cacheManager->store('phpunit:test:1', 'key1', 'value1');
        self::assertEquals(1, $this->cacheManager->delete('phpunit:test:1'));
        self::assertFalse($this->cacheManager->hasHash('phpunit:test:1'));
    }

    /**
     * @return void
     */
    public function testClearAll(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        self::assertTrue($this->cacheManager->clearAll());
    }

    /**
     * @return void
     */
    public function testGetKeyByArray(): void
    {
        print(sprintf("Testing the method %s with parameters: %s.", __METHOD__, 'none')) . PHP_EOL;

        self::assertNotNull($this->cacheManager->getKeyByParams('test'));
    }
}
