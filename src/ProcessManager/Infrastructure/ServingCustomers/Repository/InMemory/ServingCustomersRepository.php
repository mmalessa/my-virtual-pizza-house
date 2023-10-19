<?php

declare(strict_types=1);

namespace App\ProcessManager\Infrastructure\ServingCustomers\Repository\InMemory;

use App\ProcessManager\Domain\ServingCustomers\ServingCustomers;
use App\ProcessManager\Domain\ServingCustomers\ServingCustomersRepositoryInterface;

class ServingCustomersRepository implements ServingCustomersRepositoryInterface
{
    private array $storage;
    public function save(ServingCustomers $tableService)
    {
        $sagaId = $tableService->processId;
        $this->storage[$sagaId] = $tableService;
    }
    public function get(string $sagaId): ServingCustomers
    {
        if (!array_key_exists($sagaId, $this->storage)) {
            throw new \InvalidArgumentException(sprintf("TableService ID %s not found", $sagaId));
        }
        /** @var ServingCustomers $tableService */
        $tableService = $this->storage[$sagaId];
        return $tableService;
    }
}
