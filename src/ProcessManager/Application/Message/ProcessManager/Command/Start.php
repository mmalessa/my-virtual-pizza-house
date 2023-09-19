<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use App\ProcessManager\Domain\TableId;

class Start implements ProcessManagerMessageInterface
{
    public readonly TableId $tableId;

    public function __construct(TableId|string $tableId)
    {
        $this->tableId = ($tableId instanceof TableId) ? $tableId : new TableId($tableId);
    }
}
