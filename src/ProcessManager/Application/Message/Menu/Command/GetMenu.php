<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Menu\Command;
use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class GetMenu implements ProcessManagerMessageInterface
{
    public function __construct(public string $sagaId)
    {
    }
}
