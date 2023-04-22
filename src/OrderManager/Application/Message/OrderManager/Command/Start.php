<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\OrderManager\Command;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use App\OrderManager\Domain\TableId;

class Start implements OrderManagerMessageInterface
{
    public readonly TableId $tableId;

    public function __construct(TableId|string $tableId)
    {
        $this->tableId = ($tableId instanceof TableId) ? $tableId : new TableId($tableId);
    }
}