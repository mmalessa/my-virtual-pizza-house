<?php

declare(strict_types=1);

namespace App\Kitchen\Application\Message\Kitchen\Event;

use App\Kitchen\Application\Message\KitchenMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class PizzaDone implements KitchenMessageInterface
{
    public function __construct(public string $sagaId, public string $kitchenOrderId)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->kitchenOrderId) || !Uuid::isValid($this->kitchenOrderId)) {
            throw new \InvalidArgumentException("KitchenOrderId cannot be empty and must be UUID(v4)");
        }
    }
}