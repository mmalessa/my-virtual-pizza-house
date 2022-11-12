<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\WaiterMessageInterface;

class PlaceOrder implements WaiterMessageInterface
{
    public function __construct(
        public readonly string $tableId,
        public readonly array $order,
        public readonly string $timestamp
    )
    {
    }
}