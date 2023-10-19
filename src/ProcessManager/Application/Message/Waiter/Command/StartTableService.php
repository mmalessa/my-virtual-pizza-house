<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

class StartTableService implements ProcessManagerMessageInterface
{
    public function __construct(
        string $processId,
        string $tableId
    )
    {
    }
}
