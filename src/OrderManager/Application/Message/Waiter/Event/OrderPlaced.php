<?php

namespace App\OrderManager\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;

class OrderPlaced implements WaiterMessageInterface
{
    public function __construct(
        public readonly string $tableId,
        public readonly array $order,
        public readonly string $timestamp
    )
    {
        if ($this->tableId === '') {
            throw new \InvalidArgumentException('$tableId cannot be empty');
        }
        if ($this->order === []) {
            throw new \InvalidArgumentException('$order cannot be empty');
        }
        if ($this->timestamp === '') {
            throw new \InvalidArgumentException('$timestamp cannot be empty');
        }
        $dateFormat = 'Y-m-d H:i:s';
        $d = \DateTime::createFromFormat($dateFormat, $this->timestamp);
        if ($d->format($dateFormat) !== $this->timestamp) {
            throw new \InvalidArgumentException('$timestamp has invalid format');
        }
    }
}