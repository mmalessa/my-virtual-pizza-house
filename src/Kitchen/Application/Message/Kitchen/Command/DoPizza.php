<?php

declare(strict_types=1);

namespace App\Kitchen\Application\Message\Kitchen\Command;

use App\Kitchen\Application\Message\KitchenMessageInterface;
use Ramsey\Uuid\Uuid;

class DoPizza implements KitchenMessageInterface
{
    public function __construct(
        public string $sagaId,
        public string $kitchenOrderId,
        public string $pizzaId,
        public string $pizzaSize
    )
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->kitchenOrderId) || !Uuid::isValid($this->kitchenOrderId)) {
            throw new \InvalidArgumentException("KitchenOrderId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->pizzaId)) {
            throw new \InvalidArgumentException("PizzaId cannot be empty");
        }
        if (empty($this->pizzaSize)) {
            throw new \InvalidArgumentException("PizzaSize cannot be empty");
        }
    }
}