<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Waiter\Event;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
readonly class TableServiceStarted implements OrderManagerMessageInterface
{
    public function __construct(public string $tableId)
    {
        if (empty($this->tableId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
    }
}