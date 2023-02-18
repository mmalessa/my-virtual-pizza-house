<?php

declare(strict_types=1);

namespace App\OrderManager\Domain;

enum KitchenOrderStatus
{
    case Todo;
    case Done;
}