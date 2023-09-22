<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class ShowMenu implements ProcessManagerMessageInterface
{
    public function __construct(public string $sagaId, public array $menu)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
    }
}