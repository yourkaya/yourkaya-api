<?php

declare(strict_types=1);

namespace App\DTO\Payload;

use Symfony\Component\Validator\Constraints as Assert;

final class UpdateProductPayload
{
    #[
        Assert\Length(min: 3, max: 255),
        Assert\NotBlank(),
        Assert\Type(type: 'string'),
    ]
    private ?string $name = null;

    #[
        Assert\NotBlank(),
        Assert\Range(min: 100),
        Assert\Type(type: 'integer'),
    ]
    private ?int $price = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isNameSet(): bool
    {
        return null !== $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function isPriceSet(): bool
    {
        return null !== $this->price;
    }
}
