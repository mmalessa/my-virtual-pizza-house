<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\ServingCustomers;

enum KitchenOrderStatus
{
    case Todo;
    case Done;
}
