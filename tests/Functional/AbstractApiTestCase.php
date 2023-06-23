<?php

declare(strict_types=1);

namespace App\Tests\Functional;

class AbstractApiTestCase extends AbstractKernelBrowserTestCase
{
    protected const DEFAULT_HEADERS = [
        'CONTENT_TYPE' => 'application/json',
    ];

    public static function httpPost(string $uri, array|string $request, array $options = []): string
    {
        return static::httpWrite('POST', $uri, $request, $options);
    }

    public static function httpPatch(string $uri, array|string $request, array $options = []): string
    {
        return self::httpWrite('PATCH', $uri, $request, $options);
    }

    public static function httpGet(string $uri): string
    {
        static::$client->request('GET', $uri, [], [], static::DEFAULT_HEADERS);

        return static::$client->getResponse()->getContent();
    }

    protected static function httpWrite(string $method, string $uri, array|string $request, array $options = []): string
    {
        static::$client->request(
            method: $method,
            uri: $uri,
            parameters: $request,
            server: array_merge($options, static::DEFAULT_HEADERS)
        );

        return static::$client->getResponse()->getContent();
    }
}