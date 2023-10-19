<?php

declare(strict_types=1);

namespace App\ProcessManager\Infrastructure\ServingCustomers\Repository\Redis;

use App\ProcessManager\Domain\ServingCustomers\ServingCustomers;
use App\ProcessManager\Domain\ServingCustomers\ServingCustomersRepositoryInterface;
use Predis\Client;

class ServingCustomersRepository implements ServingCustomersRepositoryInterface
{
    public function __construct(private readonly Client $client, private readonly ?int $ttl)
    {
    }
    public function get(string $sagaId): ServingCustomers
    {
        if(!$this->client->exists($sagaId)) {
            throw new \InvalidArgumentException(sprintf("TableService ID %s not found", $sagaId));
        }
        /** @var ServingCustomers $tableService */
        $tableService=unserialize($this->client->get($sagaId));
        return $tableService;
    }

    public function save(ServingCustomers $tableService)
    {
        if ($this->ttl !== null) {
            $this->client->setex($tableService->processId, $this->ttl, serialize($tableService));
        } else {
            $this->client->set($tableService->processId, serialize($tableService));
        }
    }
}
