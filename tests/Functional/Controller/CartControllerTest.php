<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CartControllerTest extends WebTestCase
{
    public function test_add_product(): void
    {
        $client = self::createClient();

        $this->loadFixtures(
            $product = new Product(\uuid_create(), 'FooBar', 1000),
            $cart = new Cart(\uuid_create()),
        );

        $payload = <<<JSON
{
    "id": "{$product->getId()}"
}
JSON;

        $client->request(
            'POST',
            "/carts/{$cart->getId()}/products",
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
    }

    public function test_add_product_failed_on_limit_reached(): void
    {
        $client = self::createClient();

        $this->loadFixtures(
            $product1 = new Product(\uuid_create(), 'FooBar#1', 1100),
            $product2 = new Product(\uuid_create(), 'FooBar#2', 1200),
            $product3 = new Product(\uuid_create(), 'FooBar#3', 1300),
            $product4 = new Product(\uuid_create(), 'FooBar#4', 1400),
            $cart = (new Cart(\uuid_create()))
                ->addProduct($product1)
                ->addProduct($product2)
                ->addProduct($product3),
        );

        $payload = <<<JSON
{
    "id": "{$product4->getId()}"
}
JSON;

        $client->request(
            'POST',
            "/carts/{$cart->getId()}/products",
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'ACCEPT' => 'application/json',
            ],
            $payload
        );

        $response = $client->getResponse();

        self::assertEquals(409, $response->getStatusCode(), $response->getContent());
    }

    private function loadFixtures(object ...$entities): void
    {
        /** @var EntityManagerInterface $entityManager */
        $entityManager = self::$container->get('doctrine.orm.entity_manager');
        $entityManager->transactional(
            function (EntityManagerInterface $entityManager) use ($entities) {
                foreach ($entities as $entity) {
                    $entityManager->persist($entity);
                }
            }
        );
    }
}
