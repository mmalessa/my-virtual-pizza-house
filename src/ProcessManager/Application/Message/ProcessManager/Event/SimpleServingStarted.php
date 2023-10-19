<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

class SimpleServingStarted implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
