<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ShowBill;
use App\Waiter\Application\Message\Waiter\Event\BillPaid;
use App\Waiter\Domain\CommunicatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ShowBillHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly CommunicatorInterface $communicator
    )
    {
    }

    public function __invoke(ShowBill $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] ShowBill",
            $sagaId
        ));
        $this->communicator->showBill($command->bill);
        $this->logger->info(sprintf(
            "[%s] This is where communication with the client should pay. Let's simplify it.",
            $sagaId
        ));
        $this->messageBus->dispatch(new BillPaid($sagaId, $command->bill['sum']));
    }
}