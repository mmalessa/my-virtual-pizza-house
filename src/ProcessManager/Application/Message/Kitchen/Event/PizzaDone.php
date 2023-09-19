<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Kitchen\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class PizzaDone implements ProcessManagerMessageInterface
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
