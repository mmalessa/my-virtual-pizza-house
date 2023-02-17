<?php

declare(strict_types=1);

namespace App\Menu\Application\Message\Menu\Command;

use App\Menu\Application\Message\MenuMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class GetMenu implements MenuMessageInterface
{
    public function __construct(public string $sagaId)
    {
        if (empty($this->sagaId) || !Uuid::isValid($this->sagaId)) {
            throw new \InvalidArgumentException("SagaId cannot be empty and must be UUID(v4)");
        }
    }
}