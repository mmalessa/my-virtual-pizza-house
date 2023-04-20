<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ServePizzas;
use App\Waiter\Application\Message\Waiter\Event\PizzasServed;
use App\Waiter\Domain\CommunicatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ServePizzasHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly CommunicatorInterface $communicator
    )
    {
    }

    public function __invoke(ServePizzas $command)
    {
        $this->logger->info(sprintf("[%s] ServePizzas", $command->sagaId));
        $this->communicator->infoAboutServedPizzas($command->pizzas);
        $this->messageBus->dispatch(new PizzasServed($command->sagaId));
    }
}