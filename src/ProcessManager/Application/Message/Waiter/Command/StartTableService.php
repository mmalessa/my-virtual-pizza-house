<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use App\ProcessManager\Application\Saga\SagaId;
use App\ProcessManager\Domain\TableId;

class StartTableService implements ProcessManagerMessageInterface
{
    public readonly SagaId $sagaId;
    public readonly TableId $tableId;

    public function __construct(
        SagaId|string $sagaId,
        TableId|string $tableId
    )
    {
        $this->tableId = ($tableId instanceof TableId) ? $tableId : new TableId($tableId);
        $this->sagaId = ($sagaId instanceof SagaId) ? $sagaId : new SagaId($sagaId);
    }
}
