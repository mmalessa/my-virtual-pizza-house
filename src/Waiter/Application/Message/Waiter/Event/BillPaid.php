<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\WaiterMessageInterface;
use Ramsey\Uuid\Uuid;

class BillPaid implements WaiterMessageInterface
{
    public function __construct(public string $sagaId, public array $sum)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->sum)) {
            throw new \InvalidArgumentException("Sum cannot be empty");
        }
    }
}