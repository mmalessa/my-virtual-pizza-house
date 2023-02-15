<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\WaiterMessageInterface;

readonly class ShowMenu implements WaiterMessageInterface
{
    public function __construct(public string $sagaId, public array $menu)
    {
        if (empty($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty");
        }
    }
}