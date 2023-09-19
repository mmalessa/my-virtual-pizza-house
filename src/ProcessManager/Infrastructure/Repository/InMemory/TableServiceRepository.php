<?php

declare(strict_types=1);

namespace App\ProcessManager\Infrastructure\Repository\InMemory;

use App\ProcessManager\Domain\TableService;
use App\ProcessManager\Domain\TableServiceRepositoryInterface;

class TableServiceRepository implements TableServiceRepositoryInterface
{
    private array $storage;
    public function save(TableService $tableService)
    {
        $sagaId = $tableService->sagaId;
        $this->storage[$sagaId] = $tableService;
    }
    public function get(string $sagaId): TableService
    {
        if (!array_key_exists($sagaId, $this->storage)) {
            throw new \InvalidArgumentException(sprintf("TableService ID %s not found", $sagaId));
        }
        /** @var TableService $tableService */
        $tableService = $this->storage[$sagaId];
        return $tableService;
    }
}
