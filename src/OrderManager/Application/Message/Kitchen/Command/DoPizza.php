<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Kitchen\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class DoPizza implements OrderManagerMessageInterface
{
    public function __construct(
        public string $sagaId,
        public string $kitchenOrderId,
        public string $menuId,
        public string $pizzaSize
    )
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->kitchenOrderId) || !Uuid::isValid($this->kitchenOrderId)) {
            throw new \InvalidArgumentException("KitchenOrderId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->menuId)) {
            throw new \InvalidArgumentException("MenuId cannot be empty");
        }
        if (empty($this->pizzaSize)) {
            throw new \InvalidArgumentException("PizzaSize cannot be empty");
        }
    }
}