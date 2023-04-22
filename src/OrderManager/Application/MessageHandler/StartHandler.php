<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler;

use App\OrderManager\Application\Message\OrderManager\Command\Start;
use App\OrderManager\Application\Message\OrderManager\Event\TableServiceStarted;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\MessageBusInterface;

class StartHandler
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private LoggerInterface $logger
    ) {
    }

    public function __invoke(Start $command)
    {
        $sagaId = Uuid::uuid4()->toString();

        $this->logger->info(sprintf(
            "We start with table: %s (New SagaId: %s)",
            $command->tableId,
            $sagaId
        ));

        $this->messageBus->dispatch(new TableServiceStarted(
            $sagaId,
            $command->tableId
        ));
    }
}