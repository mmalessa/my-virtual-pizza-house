<?php

declare(strict_types=1);

namespace App\HeavyWorker\Application\Message\HeavyWorker\Command;

use App\HeavyWorker\Application\Message\HeavyWorkerMessageInterface;

readonly class DoSomethingHeavy implements HeavyWorkerMessageInterface
{
    public function __construct(public int $workingTime)
    {
    }
}