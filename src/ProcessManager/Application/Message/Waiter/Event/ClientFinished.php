<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

class ClientFinished implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
