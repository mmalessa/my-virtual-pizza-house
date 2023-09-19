<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain;

interface TableServiceRepositoryInterface
{
    public function get(string $sagaId): TableService;
    public function save(TableService $tableService);
}
