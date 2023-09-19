<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Saga;

use App\ProcessManager\Application\Message\Kitchen\Command\DoPizza;
use App\ProcessManager\Application\Message\Kitchen\Event\PizzaDone;
use App\ProcessManager\Application\Message\Menu\Command\GetMenu;
use App\ProcessManager\Application\Message\Menu\Event\MenuGot;
use App\ProcessManager\Application\Message\ProcessManager\Event\TableServiceStarted;
use App\ProcessManager\Application\Message\Waiter\Command\PlaceOrder;
use App\ProcessManager\Application\Message\Waiter\Command\ServePizzas;
use App\ProcessManager\Application\Message\Waiter\Command\ShowBill;
use App\ProcessManager\Application\Message\Waiter\Command\ShowMenu;
use App\ProcessManager\Application\Message\Waiter\Command\ThankClient;
use App\ProcessManager\Application\Message\Waiter\Event\BillPaid;
use App\ProcessManager\Application\Message\Waiter\Event\MenuShown;
use App\ProcessManager\Application\Message\Waiter\Event\OrderPlaced;
use App\ProcessManager\Application\Message\Waiter\Event\PizzasServed;
use App\ProcessManager\Domain\TableService;
use App\ProcessManager\Domain\TableServiceRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class TableServiceSaga
{
    public function __construct(
        protected readonly MessageBusInterface $messageBus,
        protected readonly LoggerInterface $logger,
        protected readonly TableServiceRepositoryInterface $tableServiceRepository
    )
    {
    }

    #[AsMessageHandler]
    public function onTableServiceStarted(TableServiceStarted $event): void {
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

    #[AsMessageHandler]
    public function onMenuGot(MenuGot $event): void {
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

    #[AsMessageHandler]
    public function onMenuShown(MenuShown $event): void {
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

    #[AsMessageHandler]
    public function onOrderPlaced(OrderPlaced $event): void {
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

    #[AsMessageHandler]
    public function onPizzaDone(PizzaDone $event): void {
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

    #[AsMessageHandler]
    public function onPizzasServed(PizzasServed $event): void {
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

    #[AsMessageHandler]
    public function onBillPaid(BillPaid $event): void {
        $sagaId = $event->sagaId;
        $this->logger->info(sprintf("[%s] onBillPaid", $sagaId));
        $tableService = $this->tableServiceRepository->get($sagaId);
        $tableService->finishService();
        $this->tableServiceRepository->save($tableService);
        $this->logger->info(sprintf("[%s] Dispatch->ThankClient", $sagaId));
        $this->messageBus->dispatch(new ThankClient($sagaId));
    }
}
