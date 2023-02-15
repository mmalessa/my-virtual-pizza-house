<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Waiter\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;

readonly class ShowMenu implements OrderManagerMessageInterface
{
    public function __construct(public string $sagaId, public array $menu)
    {
        if (empty($this->sagaId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
    }
}