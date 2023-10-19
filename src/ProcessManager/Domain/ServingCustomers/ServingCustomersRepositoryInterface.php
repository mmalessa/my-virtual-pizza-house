<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\ServingCustomers;

interface ServingCustomersRepositoryInterface
{
    public function get(string $sagaId): ServingCustomers;
    public function save(ServingCustomers $tableService);
}
