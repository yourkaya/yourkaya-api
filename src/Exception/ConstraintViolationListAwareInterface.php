<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ConstraintViolationListAwareInterface
{
    public function getErrors(): ConstraintViolationListInterface;
}
