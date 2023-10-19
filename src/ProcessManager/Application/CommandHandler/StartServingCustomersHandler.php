<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\CommandHandler;

use App\ProcessManager\Application\Message\ProcessManager\Command\StartServingCustomers;
use App\ProcessManager\Application\Message\ProcessManager\Event\ServingCustomersStarted;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;


#[AsMessageHandler]
class StartServingCustomersHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(StartServingCustomers $command)
    {
        $processId = Uuid::uuid6()->toString();

        $this->logger->info(sprintf(
            "We start serving customers at table: %s (New ProcessId: %s)",
            $command->tableId,
            $processId
        ));

        $this->messageBus->dispatch(new ServingCustomersStarted(
            $processId,
            $command->tableId
        ));
    }
}
