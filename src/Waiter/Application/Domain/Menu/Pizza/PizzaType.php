<?php

declare(strict_types=1);

namespace App\Waiter\Application\Domain\Menu\Pizza;

enum PizzaType
{
    case Margherita;
    case Funghi;
    case Vegetariana;
    case Romana;
    case Amatriciana;
}