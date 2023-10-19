<?php

declare(strict_types=1);

namespace App\Kitchen\Application\Message\Kitchen\Event;

use App\Kitchen\Application\Message\KitchenMessageInterface;

readonly class PizzaDone implements KitchenMessageInterface
{
    public function __construct(public string $processId, public string $kitchenOrderId)
    {
    }
}
