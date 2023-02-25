<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Waiter\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class ShowBill implements OrderManagerMessageInterface
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