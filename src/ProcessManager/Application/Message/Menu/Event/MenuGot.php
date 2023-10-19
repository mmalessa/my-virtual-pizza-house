<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Menu\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

readonly class MenuGot implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $menu)
    {
    }
}
