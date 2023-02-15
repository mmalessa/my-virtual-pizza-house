<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

readonly class OrderPlaced implements WaiterMessageInterface
{
    public function __construct(public string $sagaId, public array $orderList)
    {
        if (empty($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty");
        }
        if (empty($this->orderList)) {
            throw new \InvalidArgumentException("OrderList cannot be empty");
        }
    }
}