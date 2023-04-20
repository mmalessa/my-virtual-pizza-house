<?php

declare(strict_types=1);

namespace App\Kitchen\Application\MessageHandler;

use App\Kitchen\Application\Message\Kitchen\Command\DoPizza;
use App\Kitchen\Application\Message\Kitchen\Event\PizzaDone;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class DoPizzaHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(DoPizza $command)
    {
        $sagaId = $command->sagaId;
        $kitchenOrderId = $command->kitchenOrderId;
        $this->logger->info(sprintf(
            "[%s:%s] DoPizza: %s(%s)",
            $sagaId,
            $kitchenOrderId,
            $command->menuId,
            $command->pizzaSize
        ));

        $this->logger->info(sprintf(
            "[%s:%s] The pizza is done",
            $sagaId,
            $kitchenOrderId
        ));
        $this->messageBus->dispatch(new PizzaDone($sagaId, $kitchenOrderId));
    }
}