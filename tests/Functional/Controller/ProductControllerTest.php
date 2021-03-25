<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function test_create_product(): string
    {
        $payload = <<<'JSON'
{
    "name": "FooBar",
    "price": 9900
}
JSON;

        $client = self::createClient();
        $client->request(
            'POST',
            '/products',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json',
            ],
            $payload
        );

        $response = $client->getResponse();

        self::assertEquals(201, $response->getStatusCode(), $response->getContent());
        self::assertArrayHasKey('x-resource-id', $response->headers->all());

        $uuid = $response->headers->get('x-resource-id');

        self::assertTrue(\uuid_is_valid($uuid), $uuid);

        return $uuid;
    }

    /**
     * @depends test_create_product
     */
    public function test_update_product(string $uuid): void
    {
        $payload = <<<'JSON'
{
    "name": "FooBarBaz",
    "price": 11000
}
JSON;

        $client = self::createClient();
        $client->request(
            'PATCH',
            "/products/{$uuid}",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json',
            ],
            $payload
        );

        $response = $client->getResponse();

        self::assertEquals(204, $response->getStatusCode(), $response->getContent());
    }
}
