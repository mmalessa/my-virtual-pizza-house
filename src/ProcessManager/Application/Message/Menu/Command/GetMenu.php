<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Menu\Command;
use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use App\ProcessManager\Application\Saga\SagaId;

readonly class GetMenu implements ProcessManagerMessageInterface
{
    public readonly SagaId $sagaId;

    public function __construct(SagaId|string $sagaId)
    {
        $this->sagaId = ($sagaId instanceof SagaId) ? $sagaId : new SagaId($sagaId);
    }
}
