<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\FinishClient;
use App\Waiter\Application\Message\Waiter\Event\ClientFinished;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class FinishClientHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    ) {
    }

    public function __invoke(FinishClient $command)
    {
        $sagaId = $command->processId;
        $this->logger->info(sprintf(
            "[%s] ThankClient",
            $sagaId
        ));
        echo "---- Thank you very much for staying with us. Welcome again! ----\n";
        $this->messageBus->dispatch(new ClientFinished($command->processId));
    }
}
