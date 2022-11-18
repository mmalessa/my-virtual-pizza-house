<?php

declare(strict_types=1);

namespace App\Waiter\Domain\Menu\SoftDrink;

use App\Waiter\Domain\Menu\MenuItem;

class SoftDrink extends MenuItem
{
    public function __construct(
        public readonly SoftDrinkType $type,
        public readonly SoftDrinkCapacity $capacity
    )
    {}
}