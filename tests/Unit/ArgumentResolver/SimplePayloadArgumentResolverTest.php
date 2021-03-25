<?php

declare(strict_types=1);

namespace App\Tests\Unit\ArgumentResolver;

use App\ArgumentResolver\SimplePayloadArgumentResolver;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class SimplePayloadArgumentResolverTest extends TestCase
{
    /**
     * @dataProvider supportsDataProvider
     */
    public function test_supports(string $argumentName, bool $expected): void
    {
        $resolver = new SimplePayloadArgumentResolver(
            $this->createMock(SerializerInterface::class)
        );

        $argument = $this->createMock(ArgumentMetadata::class);
        $argument
            ->expects(self::once())
            ->method('getName')
            ->willReturn($argumentName)
        ;

        self::assertSame(
            $expected,
            $resolver->supports(
                $this->createMock(Request::class),
                $argument,
            )
        );
    }

    public function supportsDataProvider(): iterable
    {
        return [
            'success' => [
                'argumentName' => 'payload',
                'expected' => true,
            ],
            'failed' => [
                'argumentName' => 'foo',
                'expected' => false,
            ],
        ];
    }
}
