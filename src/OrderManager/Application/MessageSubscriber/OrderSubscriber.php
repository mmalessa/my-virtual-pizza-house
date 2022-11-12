<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageSubscriber;

use App\OrderManager\Application\Message\Waiter\Event\OrderPlaced;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderSubscriber implements MessageSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    )
    {
    }


    public static function getHandledMessages(): iterable
    {
        yield OrderPlaced::class => ['method' => "onOrderPlaced"];
    }

    public function onOrderPlaced(OrderPlaced $event) {
        $this->logger->info(sprintf(
            "onOrderPlaced, Table: %s, Timestamp: %s",
            $event->tableId,
            $event->timestamp
        ));

        // do something more ...and send next command ...if needed ;-)

        $this->logger->info("onOrderPlaced DONE");
    }
}