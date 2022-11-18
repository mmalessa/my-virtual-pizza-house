<?php

declare(strict_types=1);

namespace App\Waiter\Domain\Menu\SoftDrink;

enum SoftDrinkType
{
    case StillWater;
    case SoftSparklingWater;
    case SparklingWater;
    case Lemonade;
}
