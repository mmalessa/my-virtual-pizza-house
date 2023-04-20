<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler\TableService;

use App\OrderManager\Application\Message\Waiter\Command\PlaceOrder;
use App\OrderManager\Application\Message\Waiter\Event\MenuShown;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MenuShownHandler extends TableServiceAbstract
{
    public function __invoke(MenuShown $event)
    {
        $sagaId = $event->sagaId;

        $this->logger->info(sprintf(
            "[%s] onMenuShown",
            $sagaId
        ));

        $tableService = $this->tableServiceRepository->get($sagaId);
        $tableService->menuWasShown();
        $this->logger->info(sprintf("[%s] Dispatch->ShowMenu", $sagaId));
        $this->messageBus->dispatch(new PlaceOrder($sagaId));
        $this->tableServiceRepository->save($tableService);
    }
}