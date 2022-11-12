<?php

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

class OrderPlaced implements WaiterMessageInterface
{
    public function __construct(
        public readonly string $tableId,
        public readonly array $order,
        public readonly string $timestamp
    )
    {
    }
}