<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\WaiterMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class ShowBill implements WaiterMessageInterface
{
    public function __construct(public string $sagaId, public array $bill)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->bill)) {
            throw new \InvalidArgumentException("Bill cannot be empty");
        }
    }
}