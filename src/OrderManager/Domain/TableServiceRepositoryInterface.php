<?php

declare(strict_types=1);

namespace App\OrderManager\Domain;

interface TableServiceRepositoryInterface
{
    public function get(string $sagaId): TableService;
    public function save(TableService $tableService);
}