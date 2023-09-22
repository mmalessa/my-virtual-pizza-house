<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\CommandHandler;

use App\ProcessManager\Application\Message\ProcessManager\Command\Start;
use App\ProcessManager\Application\Message\ProcessManager\Event\TableServiceStarted;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;


#[AsMessageHandler]
class StartHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(Start $command)
    {
        $processId = Uuid::uuid6()->toString();

        $this->logger->info(sprintf(
            "We start with table: %s (New SagaId: %s)",
            $command->tableId,
            $processId
        ));

        $this->messageBus->dispatch(new TableServiceStarted(
            $processId,
            $command->tableId
        ));
    }
}
