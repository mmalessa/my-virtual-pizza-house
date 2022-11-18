<?php

declare(strict_types=1);

namespace App\Waiter\Application\Domain\Menu\Pizza;

use App\Waiter\Application\Domain\Menu\MenuItem;

class Pizza extends MenuItem
{
    public function __construct(
        public readonly PizzaType      $type,
        public readonly PizzaSize      $size,
        public readonly PizzaThickness $thickness,
    )
    {}
}