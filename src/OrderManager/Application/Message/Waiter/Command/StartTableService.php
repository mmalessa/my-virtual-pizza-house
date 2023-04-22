<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Waiter\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use App\OrderManager\Application\Saga\SagaId;
use App\OrderManager\Domain\TableId;

class StartTableService implements OrderManagerMessageInterface
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