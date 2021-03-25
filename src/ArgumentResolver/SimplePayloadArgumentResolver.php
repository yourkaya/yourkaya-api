<?php

declare(strict_types=1);

namespace App\ArgumentResolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

final class SimplePayloadArgumentResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return 'payload' === $argument->getName();
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $content = $request->getContent();

        if (empty($content)) {
            return;
        }

        yield $this->serializer->deserialize($content, $argument->getType(), $request->getRequestFormat());
    }
}
