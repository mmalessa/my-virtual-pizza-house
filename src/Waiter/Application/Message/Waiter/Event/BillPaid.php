<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

class BillPaid implements WaiterMessageInterface
{
    public function __construct(public string $processId, public array $sum)
    {
        if (empty($this->sum)) {
            throw new \InvalidArgumentException("Sum cannot be empty");
        }
    }
}
