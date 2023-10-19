<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Process;

use App\ProcessManager\Application\Message\Kitchen\Command\DoPizza;
use App\ProcessManager\Application\Message\Kitchen\Event\PizzaDone;
use App\ProcessManager\Application\Message\Menu\Command\GetMenu;
use App\ProcessManager\Application\Message\Menu\Event\MenuGot;
use App\ProcessManager\Application\Message\ProcessManager\Event\ServingCustomersStarted;
use App\ProcessManager\Application\Message\Waiter\Command\FinishClient;
use App\ProcessManager\Application\Message\Waiter\Command\PlaceOrder;
use App\ProcessManager\Application\Message\Waiter\Command\ServePizzas;
use App\ProcessManager\Application\Message\Waiter\Command\ShowBill;
use App\ProcessManager\Application\Message\Waiter\Command\ShowMenu;
use App\ProcessManager\Application\Message\Waiter\Event\BillPaid;
use App\ProcessManager\Application\Message\Waiter\Event\ClientFinished;
use App\ProcessManager\Application\Message\Waiter\Event\MenuShown;
use App\ProcessManager\Application\Message\Waiter\Event\OrderPlaced;
use App\ProcessManager\Application\Message\Waiter\Event\PizzasServed;
use App\ProcessManager\Domain\ServingCustomers\ServingCustomers;
use App\ProcessManager\Domain\ServingCustomers\ServingCustomersRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

class ServingCustomersProcess
{
    public function __construct(
        protected readonly MessageBusInterface $messageBus,
        protected readonly LoggerInterface $logger,
        protected readonly ServingCustomersRepositoryInterface $servingCustomersRepository
    )
    {
    }

    #[AsMessageHandler]
    public function onServingCustomersStarted(ServingCustomersStarted $event): void {
        $processId = $event->processId;
        $servingCustomers = ServingCustomers::create($processId);
        $this->servingCustomersRepository->save($servingCustomers);

        $this->logger->info(sprintf(
            "[%s] onServingCustomersStarted (tableId: %s)",
            $processId,
            $event->tableId
        ));
        $this->messageBus->dispatch(new GetMenu($processId));
    }

    #[AsMessageHandler]
    public function onMenuGot(MenuGot $event): void {
        $sagaId = $event->sagaId;
        $tableService = $this->servingCustomersRepository->get($sagaId);
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
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onMenuShown(MenuShown $event): void {
        $sagaId = $event->sagaId;

        $this->logger->info(sprintf(
            "[%s] onMenuShown",
            $sagaId
        ));

        $tableService = $this->servingCustomersRepository->get($sagaId);
        $tableService->menuWasShown();
        $this->logger->info(sprintf("[%s] Dispatch->ShowMenu", $sagaId));
        $this->messageBus->dispatch(new PlaceOrder($sagaId));
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onOrderPlaced(OrderPlaced $event): void {
        $sagaId = $event->sagaId;
        $orderList = $event->orderList;

        $this->logger->info(sprintf(
            "[%s] onOrderPlaced",
            $sagaId
        ));

        $tableService = $this->servingCustomersRepository->get($sagaId);

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
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onPizzaDone(PizzaDone $event): void {
        $sagaId = $event->sagaId;
        $kitchenOrderId = $event->kitchenOrderId;

        $tableService = $this->servingCustomersRepository->get($sagaId);
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
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onPizzasServed(PizzasServed $event): void {
        $sagaId = $event->sagaId;
        $this->logger->info(sprintf("[%s] onPizzasServed", $sagaId));

        $tableService = $this->servingCustomersRepository->get($sagaId);
        $bill = $tableService->getBill();
        $delayMs = 2000;
        $this->logger->info(sprintf(
            "[%s] Dispatch->ShowBill with delay %d ms",
            $sagaId,
            $delayMs
        ));
        $this->messageBus->dispatch(new ShowBill($sagaId, $bill));
    }

    #[AsMessageHandler]
    public function onBillPaid(BillPaid $event): void {
        $sagaId = $event->sagaId;
        $this->logger->info(sprintf("[%s] onBillPaid", $sagaId));
        $tableService = $this->servingCustomersRepository->get($sagaId);
        $tableService->finishService();
        $this->servingCustomersRepository->save($tableService);
        $this->logger->info(sprintf("[%s] Dispatch->ThankClient", $sagaId));
        $this->messageBus->dispatch(new FinishClient($sagaId));
    }

    #[AsMessageHandler]
    public function onClientFinished(ClientFinished $event): void {
        $this->logger->info("*** END OF PROCESS ***");
    }
}
