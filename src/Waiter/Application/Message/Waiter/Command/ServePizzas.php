<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\WaiterMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class ServePizzas implements WaiterMessageInterface
{
    public function __construct(public string $sagaId, public array $pizzas)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
        if (empty($this->pizzas)) {
            throw new \InvalidArgumentException("Pizzas cannot be empty");
        }
    }
}