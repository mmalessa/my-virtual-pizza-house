<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler\TableService;

use App\OrderManager\Application\Message\Kitchen\Event\PizzaDone;
use App\OrderManager\Application\Message\Waiter\Command\ServePizzas;
use App\OrderManager\Application\MessageHandler\TableServiceAbstract;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class PizzaDoneHandler extends TableServiceAbstract
{
    public function __invoke(PizzaDone $event)
    {
        $sagaId = $event->sagaId;
        $kitchenOrderId = $event->kitchenOrderId;

        $tableService = $this->tableServiceRepository->get($sagaId);
        $tableService->kitchenOrderDone($kitchenOrderId);
        $this->logger->info(sprintf(
            "[%s:%s] onPizza Done",
            $sagaId,
            $kitchenOrderId
        ));

        if ($tableService->allOrdersDone()) {
            $this->logger->info(sprintf("[%s] All Pizzas done! All pizzas can be served!", $sagaId));
            $pizzasToServe = $tableService->getPizzasToServe();
            $this->messageBus->dispatch(new ServePizzas($sagaId, $pizzasToServe));
        } else {
            $this->logger->info(sprintf("[%s] Not all pizzas ready. We are waiting.", $sagaId));
        }
        $this->tableServiceRepository->save($tableService);
    }
}