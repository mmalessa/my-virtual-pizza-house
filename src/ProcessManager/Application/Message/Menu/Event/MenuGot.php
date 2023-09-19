<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Menu\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class MenuGot implements ProcessManagerMessageInterface
{
    public function __construct(public string $sagaId, public array $menu)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
    }
}
