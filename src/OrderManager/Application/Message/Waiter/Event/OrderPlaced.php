<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Waiter\Event;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;

readonly class OrderPlaced implements OrderManagerMessageInterface
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