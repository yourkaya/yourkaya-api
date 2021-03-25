<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

final class BadPayloadException extends \Exception implements ConstraintViolationListAwareInterface
{
    private ConstraintViolationListInterface $errors;

    public function __construct(ConstraintViolationListInterface $errors, $message = '', Throwable $previous = null)
    {
        $this->errors = $errors;

        parent::__construct($message, 0, $previous);
    }

    /**
     * @return ConstraintViolationListInterface|ConstraintViolationInterface[]
     */
    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}
