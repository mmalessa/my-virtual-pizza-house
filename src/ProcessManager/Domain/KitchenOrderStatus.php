<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain;

enum KitchenOrderStatus
{
    case Todo;
    case Done;
}
