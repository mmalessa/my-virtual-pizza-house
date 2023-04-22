<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\OrderManager\Event;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use App\OrderManager\Application\Saga\SagaId;
use App\OrderManager\Domain\TableId;

readonly class TableServiceStarted implements OrderManagerMessageInterface
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