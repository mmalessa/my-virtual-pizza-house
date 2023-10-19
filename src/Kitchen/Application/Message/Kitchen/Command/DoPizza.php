<?php

declare(strict_types=1);

namespace App\Kitchen\Application\Message\Kitchen\Command;

use App\Kitchen\Application\Message\KitchenMessageInterface;

class DoPizza implements KitchenMessageInterface
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
