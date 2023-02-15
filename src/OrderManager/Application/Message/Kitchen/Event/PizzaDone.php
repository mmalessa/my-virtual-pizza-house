<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Kitchen\Event;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;

readonly class PizzaDone implements OrderManagerMessageInterface
{
    public function __construct(public string $sagaId, public string $kitchenOrderId)
    {
        if (empty($this->sagaId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
        if (empty($this->kitchenOrderId)) {
            throw new \InvalidArgumentException("KitchenOrderId cannot be empty");
        }
    }
}