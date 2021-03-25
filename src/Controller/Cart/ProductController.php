<?php

declare(strict_types=1);

namespace App\Controller\Cart;

use App\DTO\Payload\AddProductToCartPayload;
use App\Exception\BadPayloadException;
use App\Exception\ConflictException;
use App\Exception\ResourceNotFoundException;
use App\Service\CartService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ProductController
{
    private CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    #[Route(path: '/carts/{cart}/products', methods: ['POST'], format: 'json')]
    public function post(string $cart, AddProductToCartPayload $payload): JsonResponse
    {
        try {
            $id = $this->cartService->addProduct($cart, $payload);
        } catch (ConflictException $e) {
            throw new ConflictHttpException($e->getMessage(), $e);
        } catch (ResourceNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        } catch (BadPayloadException $e) {
            foreach ($e->getErrors() as $error) { // TODO: temporary solution
                throw new BadRequestHttpException(\sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage()), $e);
            }
        }

        return new JsonResponse(
            null,
            JsonResponse::HTTP_CREATED,
            [
                'X-Resource-Id' => $id,
            ]
        );
    }
}
