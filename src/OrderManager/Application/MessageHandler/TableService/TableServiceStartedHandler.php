<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler\TableService;

use App\OrderManager\Application\Message\Menu\Command\GetMenu;
use App\OrderManager\Application\Message\OrderManager\Event\TableServiceStarted;
use App\OrderManager\Domain\TableService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TableServiceStartedHandler extends TableServiceAbstract
{
    public function __invoke(TableServiceStarted $event)
    {
        $sagaId = $event->sagaId;
        $tableService = TableService::create($sagaId);
        $this->tableServiceRepository->save($tableService);

        $this->logger->info(sprintf(
            "[%s] onTableServiceStarted (tableId: %s)",
            $sagaId,
            $event->tableId
        ));
        $this->messageBus->dispatch(new GetMenu($sagaId));
    }
}