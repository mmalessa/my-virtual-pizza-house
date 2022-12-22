<?php

declare(strict_types=1);

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
        if (empty($this->tableId)) {
            throw new \InvalidArgumentException('$tableId cannot be empty');
        }
        if (empty($this->order)) {
            throw new \InvalidArgumentException('$order cannot be empty');
        }
        if (empty($this->timestamp)) {
            throw new \InvalidArgumentException('$timestamp cannot be empty');
        }
        $dateFormat = 'Y-m-d H:i:s';
        $d = \DateTime::createFromFormat($dateFormat, $this->timestamp);
        if (strcmp($d->format($dateFormat), $this->timestamp) != 0) {
            throw new \InvalidArgumentException('$timestamp has invalid format');
        }
    }
}