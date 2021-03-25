<?php

declare(strict_types=1);

namespace App\Service;

use App\Bridge\DTO\Payload\ProductEntityFactory;
use App\DTO\Payload\CreateProductPayload;
use App\DTO\Payload\UpdateProductPayload;
use App\Entity\Product;
use App\Exception\BadPayloadException;
use App\Exception\ResourceNotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ProductService
{
    private ValidatorInterface $validator;
    private EntityManagerInterface $entityManager;
    private ProductEntityFactory $productEntityFactory;

    public function __construct(
        ValidatorInterface $validator,
        ProductEntityFactory $productEntityFactory,
        EntityManagerInterface $entityManager
    )
    {
        $this->validator = $validator;
        $this->productEntityFactory = $productEntityFactory;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws BadPayloadException
     */
    public function create(CreateProductPayload $payload): string
    {
        $errors = $this->validator->validate($payload);

        if ($errors->count()) {
            throw new BadPayloadException($errors);
        }

        $product = $this->productEntityFactory->create($payload);

        $this->entityManager->transactional(
            function (EntityManagerInterface $entityManager) use ($product) {
                $entityManager->persist($product);
            }
        );

        return $product->getId();
    }

    /**
     * @throws BadPayloadException
     * @throws ResourceNotFoundException
     */
    public function update(string $id, UpdateProductPayload $payload): void
    {
        $errors = $this->validator->validate($payload);

        if ($errors->count()) {
            throw new BadPayloadException($errors);
        }

        /** @var Product $product */
        if (null === ($product = $this->entityManager->find(Product::class, $id))) {
            throw new ResourceNotFoundException("Product#{$id} not found!");
        }

        $this->productEntityFactory->update($product, $payload);

        $this->entityManager->transactional(
            function (EntityManagerInterface $entityManager) use ($product) {
                $entityManager->persist($product);
            }
        );
    }
}
