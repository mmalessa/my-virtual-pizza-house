<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

class PlaceOrder implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
