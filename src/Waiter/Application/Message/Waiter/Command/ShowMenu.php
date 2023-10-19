<?php

declare(strict_types=1);

namespace App\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\WaiterMessageInterface;

readonly class ShowMenu implements WaiterMessageInterface
{
    public function __construct(public string $processId, public array $menu)
    {
    }
}
