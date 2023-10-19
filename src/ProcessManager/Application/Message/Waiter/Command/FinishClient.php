<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class FinishClient implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
