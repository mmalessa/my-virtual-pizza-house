<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ShowMenu;
use App\Waiter\Application\Message\Waiter\Event\MenuShown;
use App\Waiter\Domain\CommunicatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ShowMenuHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly CommunicatorInterface $communicator
    )
    {
    }

    public function __invoke(ShowMenu $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] ShowMenu",
            $sagaId
        ));
        $this->communicator->showMenu($command->menu);

        $this->messageBus->dispatch(new MenuShown($sagaId));
    }


}