<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class Start implements ProcessManagerMessageInterface
{
    public function __construct(public string $tableId)
    {
    }
}
