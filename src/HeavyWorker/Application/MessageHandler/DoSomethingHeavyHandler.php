<?php

declare(strict_types=1);

namespace App\HeavyWorker\Application\MessageHandler;

use App\HeavyWorker\Application\Message\HeavyWorker\Command\DoSomethingHeavy;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DoSomethingHeavyHandler implements MessageHandlerInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function __invoke(DoSomethingHeavy $command)
    {
        $this->logger->info("I'm starting to do something heavy.");
        $workingTime = $command->workingTime;
        for ($i=1; $i<=$workingTime; $i++) {
            $this->logger->info(sprintf("I'm working... (%d/%d)", $i, $workingTime));
            sleep(1);
        }
        $this->logger->info("I ended up doing something heavy.");
    }
}