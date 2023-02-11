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
        $this->logger->info(sprintf(
            "Hi table %s, I'm a waiter. I want to accept your order.",
            $command->tableId
        ));
        $timestamp = date("Y-m-d H:i:s");
        $this->messageBus->dispatch(new OrderPlaced(
            $command->tableId,
            $command->order,
            $timestamp
        ));
        $this->logger->info(sprintf(
            "Thank you table %s. I took the order.",
            $command->tableId
        ));
    }
}