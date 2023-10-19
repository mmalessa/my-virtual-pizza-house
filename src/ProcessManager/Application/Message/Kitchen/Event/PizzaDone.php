<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Kitchen\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class PizzaDone implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public string $kitchenOrderId)
    {
    }
}
