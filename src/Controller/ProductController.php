<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Payload\CreateProductPayload;
use App\DTO\Payload\UpdateProductPayload;
use App\Exception\BadPayloadException;
use App\Exception\ResourceNotFoundException;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    #[Route(path: '/products', methods: ['POST'], format: 'json')]
    public function post(CreateProductPayload $payload): JsonResponse
    {
        try {
            $id = $this->productService->create($payload);
        } catch (BadPayloadException $e) {
            foreach ($e->getErrors() as $error) { // TODO: temporary solution
                throw new BadRequestHttpException(\sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage()));
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

    #[Route(path: '/products/{product}', methods: ['PATCH'], format: 'json')]
    public function patch(string $product, UpdateProductPayload $payload): JsonResponse
    {
        try {
            $this->productService->update($product, $payload);
        } catch (ResourceNotFoundException $e) {
            throw new NotFoundHttpException(null, $e);
        } catch (BadPayloadException $e) {
            foreach ($e->getErrors() as $error) { // TODO: temporary solution
                throw new BadRequestHttpException(\sprintf('%s: %s', $error->getPropertyPath(), $error->getMessage()));
            }
        }

        return new JsonResponse(
            null,
            JsonResponse::HTTP_NO_CONTENT
        );
    }
}
