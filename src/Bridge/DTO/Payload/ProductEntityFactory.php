<?php

declare(strict_types=1);

namespace App\Bridge\DTO\Payload;

use App\DTO\Payload\CreateProductPayload;
use App\DTO\Payload\UpdateProductPayload;
use App\Entity\Product;

final class ProductEntityFactory
{
    public function create(CreateProductPayload $payload): Product
    {
        return new Product(
            \uuid_create(),
            $payload->getName(),
            $payload->getPrice()
        );
    }

    public function update(Product $product, UpdateProductPayload $payload): void
    {
        if ($payload->isNameSet()) {
            $product->setName($payload->getName());
        }

        if ($payload->isPriceSet()) {
            $product->setPrice($payload->getPrice());
        }
    }
}
