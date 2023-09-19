<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\MessageHandler\TableService;

use App\ProcessManager\Application\Message\Menu\Event\MenuGot;
use App\ProcessManager\Application\Message\Waiter\Command\PlaceOrder;
use App\ProcessManager\Application\Message\Waiter\Command\ShowMenu;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class MenuGotHandler extends TableServiceAbstract
{
    public function __invoke(MenuGot $event)
    {
        $sagaId = $event->sagaId;
        $tableService = $this->tableServiceRepository->get($sagaId);
        $this->logger->info(sprintf(
            "[%s] onMenuGot",
            $sagaId
        ));

        // Decision based on the state of the saga
        if ($tableService->canShowMenu()) {
            $this->logger->info(sprintf("[%s] Dispatch->ShowMenu", $sagaId));
            $tableService->thisMenuWasShownToCustomer($event->menu);
            $this->messageBus->dispatch(new ShowMenu($event->sagaId, $event->menu));
        } else {
            $this->logger->info(sprintf("[%s] Menu was shown before. Dispatch->ShowMenu", $sagaId));
            $this->messageBus->dispatch(new PlaceOrder($sagaId));
        }
        $this->tableServiceRepository->save($tableService);
    }
}
