<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

readonly class TableServiceStarted implements WaiterMessageInterface
{
    public function __construct(public string $tableId)
    {
        if (empty($this->tableId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
    }
}