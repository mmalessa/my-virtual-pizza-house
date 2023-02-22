<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Menu\Command;
use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class GetMenu implements OrderManagerMessageInterface
{
    public function __construct(public string $sagaId)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
    }
}