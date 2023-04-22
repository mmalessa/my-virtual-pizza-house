<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Menu\Command;
use App\OrderManager\Application\Message\OrderManagerMessageInterface;
use App\OrderManager\Application\Saga\SagaId;

readonly class GetMenu implements OrderManagerMessageInterface
{
    public readonly SagaId $sagaId;

    public function __construct(SagaId|string $sagaId)
    {
        $this->sagaId = ($sagaId instanceof SagaId) ? $sagaId : new SagaId($sagaId);
    }
}