<?php

declare(strict_types=1);

namespace App\Kitchen\Application\Message\Kitchen\Event;

use App\Kitchen\Application\Message\KitchenMessageInterface;

readonly class PizzaDone implements KitchenMessageInterface
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