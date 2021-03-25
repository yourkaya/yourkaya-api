<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\Payload\AddProductToCartPayload;
use App\Entity\Cart;
use App\Entity\Product;
use App\Exception\BadPayloadException;
use App\Exception\ConflictException;
use App\Exception\ResourceNotFoundException;
use App\Repository\CartRepository;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class CartService
{
    const PRODUCT_MAX_COUNT = 3;
    private ValidatorInterface $validator;
    private CartRepository $cartRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ValidatorInterface $validator, CartRepository $cartRepository, EntityManagerInterface $entityManager)
    {
        $this->validator = $validator;
        $this->cartRepository = $cartRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws BadPayloadException
     * @throws ConflictException
     * @throws ResourceNotFoundException
     */
    public function addProduct(string $id, AddProductToCartPayload $payload): string
    {
        $errors = $this->validator->validate($payload);

        if ($errors->count()) {
            throw new BadPayloadException($errors);
        }

        $this->entityManager->beginTransaction();

        /** @var Cart $cart */
        if (null === ($cart = $this->cartRepository->find($id, LockMode::PESSIMISTIC_WRITE))) {
            throw new ResourceNotFoundException('Cart not found!');
        }

        if (self::PRODUCT_MAX_COUNT <= $this->cartRepository->countProducts($cart->getId())) {
            throw new ConflictException('Product limit reached!');
        }

        /** @var Product $product */
        if (null === ($product = $this->entityManager->find(Product::class, $payload->getId()))) {
            throw new ResourceNotFoundException('Product not found!');
        }

        $cart->addProduct($product);

        try {
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $e) {
            $this->entityManager->close();
            $this->entityManager->rollback();

            throw $e;
        }

        return $product->getId();
    }
}
