<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler\TableService;

use App\OrderManager\Application\Message\Menu\Command\GetMenu;
use App\OrderManager\Application\Message\Waiter\Event\TableServiceStarted;
use App\OrderManager\Domain\TableService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class TableServiceStartedHandler extends TableServiceAbstract
{
    public function __invoke(TableServiceStarted $event)
    {
        $sagaId = Uuid::uuid4()->toString();

        $tableService = new TableService($sagaId);
        $this->tableServiceRepository->save($tableService);

        $this->logger->info(sprintf(
            "[%s] onTableServiceStarted (tableId: %s, sagaId: %s)",
            $sagaId,
            $event->tableId,
            $sagaId
        ));
        $this->messageBus->dispatch(new GetMenu($sagaId));
    }
}