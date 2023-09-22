<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class OrderPlaced implements ProcessManagerMessageInterface
{
    public function __construct(public string $sagaId, public array $orderList)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->orderList)) {
            throw new \InvalidArgumentException("OrderList cannot be empty");
        }
    }
}