<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PlaceOrderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(PlaceOrder $command)
    {
        $this->logger->info("PlaceOrderHandler");
        $this->messageBus->dispatch(new OrderPlaced(
            $command->tableId,
            $command->order
        ));
        $this->logger->info("PlaceOrderHandler Done");
    }
}