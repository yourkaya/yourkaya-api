<?php

declare(strict_types=1);

namespace App\Tests\Unit\Bridge\DTO\Payload;

use App\Bridge\DTO\Payload\ProductEntityFactory;
use App\DTO\Payload\CreateProductPayload;
use App\DTO\Payload\UpdateProductPayload;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;

class ProductEntityFactoryTest extends TestCase
{
    public function test_create(): Product
    {
        $product = (new ProductEntityFactory())->create(
            (new CreateProductPayload())
                ->setName('FooBar')
                ->setPrice(10000)
        );

        self::assertTrue(\uuid_is_valid($product->getId()), $product->getId());
        self::assertSame('FooBar', $product->getName());
        self::assertSame(10000, $product->getPrice());

        return $product;
    }

    /**
     * @depends test_create
     */
    public function test_update(Product $product): void
    {
        $previous = clone $product;

        (new ProductEntityFactory())->update(
            $product,
            (new UpdateProductPayload())
                ->setName('Baz')
                ->setPrice(5000)
        );

        self::assertSame($previous->getId(), $product->getId());
        self::assertSame('Baz', $product->getName());
        self::assertSame(5000, $product->getPrice());
    }
}
