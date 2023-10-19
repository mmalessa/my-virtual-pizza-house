<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

readonly class OrderPlaced implements WaiterMessageInterface
{
    public function __construct(public string $processId, public array $orderList)
    {
        if (empty($this->orderList)) {
            throw new \InvalidArgumentException("OrderList cannot be empty");
        }
    }
}
