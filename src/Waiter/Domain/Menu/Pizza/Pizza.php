<?php

declare(strict_types=1);

namespace App\Waiter\Domain\Menu\Pizza;

use App\Waiter\Domain\Menu\MenuItem;

class Pizza extends MenuItem
{
    public function __construct(
        public readonly PizzaType      $type,
        public readonly PizzaSize      $size,
        public readonly PizzaThickness $thickness,
    )
    {}
}