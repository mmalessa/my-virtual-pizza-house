<?php

declare(strict_types=1);

namespace App\OrderManager\Infrastructure\Repository\Redis;

use App\OrderManager\Domain\TableService;
use App\OrderManager\Domain\TableServiceRepositoryInterface;
use Predis\Client;

class TableServiceRepository implements TableServiceRepositoryInterface
{
    public function __construct(private readonly Client $client, private readonly ?int $ttl)
    {
    }
    public function get(string $sagaId): TableService
    {
        if(!$this->client->exists($sagaId)) {
            throw new \InvalidArgumentException(sprintf("TableService ID %s not found", $sagaId));
        }
        /** @var TableService $tableService */
        $tableService=unserialize($this->client->get($sagaId));
        return $tableService;
    }

    public function save(TableService $tableService)
    {
        if ($this->ttl !== null) {
            $this->client->setex($tableService->sagaId, $this->ttl, serialize($tableService));
        } else {
            $this->client->set($tableService->sagaId, serialize($tableService));
        }
    }
}