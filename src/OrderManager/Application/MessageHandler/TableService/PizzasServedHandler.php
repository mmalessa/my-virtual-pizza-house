<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageHandler\TableService;

use App\OrderManager\Application\Message\Waiter\Command\ShowBill;
use App\OrderManager\Application\Message\Waiter\Event\PizzasServed;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Stamp\DelayStamp;

#[AsMessageHandler]
class PizzasServedHandler extends TableServiceAbstract
{
    public function __invoke(PizzasServed $event)
    {
        $sagaId = $event->sagaId;
        $this->logger->info(sprintf("[%s] onPizzasServed", $sagaId));

        $tableService = $this->tableServiceRepository->get($sagaId);
        $bill = $tableService->getBill();
        $delayMs = 2000;
        $this->logger->info(sprintf(
            "[%s] Dispatch->ShowBill with delay %d ms",
            $sagaId,
            $delayMs
        ));
        $this->messageBus->dispatch(new ShowBill($sagaId, $bill), [new DelayStamp($delayMs)]);
    }
}