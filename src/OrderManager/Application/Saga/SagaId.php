<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Saga;

use Ramsey\Uuid\Uuid;

class SagaId implements \Stringable
{
    public function __construct(private string $sagaId)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
    }

    public function __toString(): string
    {
        return $this->sagaId;
    }
}