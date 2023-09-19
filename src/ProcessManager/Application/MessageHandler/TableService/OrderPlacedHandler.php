<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\MessageHandler\TableService;

use App\ProcessManager\Application\Message\Kitchen\Command\DoPizza;
use App\ProcessManager\Application\Message\Waiter\Event\OrderPlaced;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class OrderPlacedHandler extends TableServiceAbstract
{
    public function __invoke(OrderPlaced $event)
    {
        $sagaId = $event->sagaId;
        $orderList = $event->orderList;

        $this->logger->info(sprintf(
            "[%s] onOrderPlaced",
            $sagaId
        ));

        $tableService = $this->tableServiceRepository->get($sagaId);

        foreach ($orderList as $order) {
            for ($q=1; $q<=$order['quantity']; $q++) {
                $kitchenOrderId = Uuid::uuid4()->toString();
                $menuId = $order['id'];
                $pizzaSize = $order['size'];
                $tableService->addKitchenOrder($kitchenOrderId, $menuId, $pizzaSize);
                $this->logger->info(sprintf(
                    "[%s:%s] Dispatch->DoPizza %s(%s)",
                    $sagaId,
                    $kitchenOrderId,
                    $order['id'],
                    $order['size']
                ));
                $this->messageBus->dispatch(new DoPizza($sagaId, $kitchenOrderId, $order['id'], $order['size']));
            }
        }
        $this->tableServiceRepository->save($tableService);
    }
}
