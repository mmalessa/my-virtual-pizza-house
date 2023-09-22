<?php

declare(strict_types=1);

namespace App\Menu\Application\MessageHandler;

use App\Menu\Application\Message\Menu\Command\GetMenu;
use App\Menu\Application\Message\Menu\Event\MenuGot;
use App\Menu\Domain\Query\GetMenuQueryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class GetMenuHandler
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly GetMenuQueryInterface $getMenuQuery
    )
    {
    }

    public function __invoke(GetMenu $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] GetMenu received",
            $sagaId
        ));
        $menu = $this->getMenuQuery->getMenu();
        $this->messageBus->dispatch(new MenuGot($sagaId, $menu));
        $this->logger->info(sprintf(
            "[%s] MenuGot dispatched (%d items)",
            $sagaId,
            count($menu)
        ));
    }
}
