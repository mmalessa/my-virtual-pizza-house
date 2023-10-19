<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Kitchen\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class DoPizza implements ProcessManagerMessageInterface
{
    public function __construct(
        public string $processId,
        public string $kitchenOrderId,
        public string $menuId,
        public string $pizzaSize
    )
    {
        if (empty($this->menuId)) {
            throw new \InvalidArgumentException("MenuId cannot be empty");
        }
        if (empty($this->pizzaSize)) {
            throw new \InvalidArgumentException("PizzaSize cannot be empty");
        }
    }
}
