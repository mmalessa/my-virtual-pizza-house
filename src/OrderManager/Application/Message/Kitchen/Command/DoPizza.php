<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Kitchen\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use Symfony\Component\HttpKernel\KernelInterface;

readonly class DoPizza implements OrderManagerMessageInterface
{
    public function __construct(
        public string $sagaId,
        public string $kitchenOrderId,
        public string $pizzaId,
        public string $pizzaSize
    )
    {
        if (empty($this->sagaId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
        if (empty($this->kitchenOrderId)) {
            throw new \InvalidArgumentException("KitchenOrderId cannot be empty");
        }
        if (empty($this->pizzaId)) {
            throw new \InvalidArgumentException("PizzaId cannot be empty");
        }
        if (empty($this->pizzaSize)) {
            throw new \InvalidArgumentException("PizzaSize cannot be empty");
        }
    }
}