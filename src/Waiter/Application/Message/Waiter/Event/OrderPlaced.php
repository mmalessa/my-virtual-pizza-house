<?php

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

class OrderPlaced implements WaiterMessageInterface
{
    public function __construct(
        public string $tableId
    )
    {
    }
}