<?php

declare(strict_types=1);

namespace App\HeavyWorker\Application\MessageHandler;

use App\HeavyWorker\Application\Message\HeavyWorker\Command\DoSomethingHeavy;
use Mmalessa\SomeTools\SomeDelayInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DoSomethingHeavyHandler
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly SomeDelayInterface $delay
    ) {
    }

    public function __invoke(DoSomethingHeavy $command)
    {
        $this->logger->info("I'm starting to do something heavy.");
        $workingTime = $command->workingTime;
        for ($i=1; $i<=$workingTime; $i++) {
            $this->logger->info(sprintf("I'm working... (%d/%d)", $i, $workingTime));
            $this->delay->delay();
        }
        $this->logger->info("I ended up doing something heavy.");
    }
}
