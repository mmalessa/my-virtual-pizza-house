<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use App\ProcessManager\Application\Saga\SagaId;
use App\ProcessManager\Domain\TableId;

readonly class TableServiceStarted implements ProcessManagerMessageInterface
{
    public readonly SagaId $sagaId;
    public readonly TableId $tableId;

    public function __construct(
        SagaId|string $sagaId,
        TableId|string $tableId
    ) {
        $this->sagaId = ($sagaId instanceof SagaId) ? $sagaId : new SagaId($sagaId);
        $this->tableId = ($tableId instanceof TableId) ? $tableId : new TableId($tableId);
    }
}
