<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

class ClientFinished implements WaiterMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
