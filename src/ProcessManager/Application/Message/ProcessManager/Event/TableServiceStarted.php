<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class TableServiceStarted implements ProcessManagerMessageInterface
{
    public function __construct(
        public string $processId,
        public string $tableId
    ) {
    }
}
