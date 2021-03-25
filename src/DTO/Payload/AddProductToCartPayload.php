<?php

declare(strict_types=1);

namespace App\DTO\Payload;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Uuid;

final class AddProductToCartPayload
{
    #[
        NotBlank(),
        Uuid(),
    ]
    private string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }
}
