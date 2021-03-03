<?php

declare(strict_types=1);

namespace WileyTechnicalChallenge;

use JsonException;
use PHPUnit\Framework\TestCase;

/**
 * Class SimpleJsonRequestTest
 */
final class SimpleJsonRequestTest extends TestCase
{
    /**
     * @var SimpleJsonRequest
     */
    protected SimpleJsonRequest $simpleJsonRequest;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->simpleJsonRequest = new SimpleJsonRequest(
            new CacheManager(
                new CacheClientFacade(
                    getenv('REDIS_HOST'),
                    (int)getenv('REDIS_PORT')
                )
            )
        );
    }

    /**
     * @dataProvider getPayload
     *
     * @param string $url
     * @param array  $parameters
     *
     * @return void
     * @throws JsonException
     */
    public function testGet(string $url, array $parameters = []): void
    {
        print(
        sprintf(
            "Testing the method %s with parameters: %s and %s.",
            __METHOD__,
            $url,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        )
        );

        self::assertNotNull($this->simpleJsonRequest->get($url, $parameters));
    }

    /**
     * @dataProvider postPayload
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return void
     * @throws JsonException
     */
    public function testPost(string $url, array $parameters = [], array $data = []): void
    {
        print(
        sprintf(
            "Testing the method %s with parameters: %s and %s.",
            __METHOD__,
            $url,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        )
        );

        self::assertNotNull($this->simpleJsonRequest->post($url, $parameters, $data));
    }

    /**
     * @dataProvider putPayload
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return void
     * @throws JsonException
     */
    public function testPut(string $url, array $parameters = [], array $data = []): void
    {
        print(
        sprintf(
            "Testing the method %s with parameters: %s and %s.",
            __METHOD__,
            $url,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        )
        );

        self::assertNotNull($this->simpleJsonRequest->put($url, $parameters, $data));
    }

    /**
     * @dataProvider patchPayload
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return void
     * @throws JsonException
     */
    public function testPatch(string $url, array $parameters = [], array $data = []): void
    {
        print(
        sprintf(
            "Testing the method %s with parameters: %s and %s.",
            __METHOD__,
            $url,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        )
        );

        self::assertNotNull($this->simpleJsonRequest->patch($url, $parameters, $data));
    }

    /**
     * @dataProvider deletePayload
     *
     * @param string $url
     * @param array  $parameters
     * @param array  $data
     *
     * @return void
     * @throws JsonException
     */
    public function testDelete(string $url, array $parameters = [], array $data = []): void
    {
        print(
        sprintf(
            "Testing the method %s with parameters: %s and %s.",
            __METHOD__,
            $url,
            json_encode($parameters, JSON_THROW_ON_ERROR)
        )
        );

        self::assertNotNull($this->simpleJsonRequest->delete($url, $parameters, $data));
    }

    /**
     * @return array[]
     */
    public function getPayload(): array
    {
        return [
            [
                'https://httpbin.org/get',
            ],
            [
                'https://httpbin.org/get',
                ['name' => 'Wiley'],
            ],
            [
                'http://www.google.com/search',
                ['q' => 'Wiley'],
            ],
            [
                'http://www.google.com/search',
                ['q' => 'Wiley'],
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function postPayload(): array
    {
        return [
            [
                'https://httpbin.org/post',
                [],
                [
                    'name'     => 'John Doe',
                    'birthday' => '1984-03-01',
                    'document' => '123.321.12-X',
                ],
            ],
            [
                'https://httpbin.org/post',
                [],
                [
                    'name'     => 'John Doe',
                    'birthday' => '1984-03-01',
                    'document' => '123.321.12-X',
                ],
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function putPayload(): array
    {
        return [
            [
                'https://httpbin.org/put',
                ['id' => 1234],
                [
                    'id'       => 1234,
                    'name'     => 'John Doe',
                    'birthday' => '1984-03-01',
                    'document' => '123.321.12-X',
                ],
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function patchPayload(): array
    {
        return [
            [
                'https://httpbin.org/patch',
                [],
                [],
            ],
        ];
    }

    /**
     * @return array[]
     */
    public function deletePayload(): array
    {
        return [
            [
                'https://httpbin.org/delete',
                ['id' => 1234],
                ['user' => 88],
            ],
        ];
    }
}
