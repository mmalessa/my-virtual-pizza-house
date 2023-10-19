<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Process;

use App\ProcessManager\Application\Message\Kitchen\Command\DoPizza;
use App\ProcessManager\Application\Message\Kitchen\Event\PizzaDone;
use App\ProcessManager\Application\Message\Menu\Command\GetMenu;
use App\ProcessManager\Application\Message\Menu\Event\MenuGot;
use App\ProcessManager\Application\Message\ProcessManager\Command\StartServingCustomers;
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
    private const PROCESS_NAME = "ServingCustomers";

    public function __construct(
        protected readonly MessageBusInterface $messageBus,
        protected readonly LoggerInterface $logger,
        protected readonly ServingCustomersRepositoryInterface $servingCustomersRepository
    )
    {
    }

    #[AsMessageHandler]
    public function startProcess(StartServingCustomers $command): void
    {
        $processId = sprintf(
            "%s:%s",
            self::PROCESS_NAME,
            Uuid::uuid6()->toString()
        );

        $this->logger->info(sprintf(
            "We start serving customers at table: %s (New ProcessId: %s)",
            $command->tableId,
            $processId
        ));

        $this->messageBus->dispatch(new ServingCustomersStarted(
            $processId,
            $command->tableId
        ));
    }

    #[AsMessageHandler]
    public function onServingCustomersStarted(ServingCustomersStarted $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
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
    public function onMenuGot(MenuGot $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $tableService = $this->servingCustomersRepository->get($processId);
        $this->logger->info(sprintf(
            "[%s] onMenuGot",
            $processId
        ));

        // Decision based on the state of the saga
        if ($tableService->canShowMenu()) {
            $this->logger->info(sprintf("[%s] Dispatch->ShowMenu", $processId));
            $tableService->thisMenuWasShownToCustomer($event->menu);
            $this->messageBus->dispatch(new ShowMenu($event->processId, $event->menu));
        } else {
            $this->logger->info(sprintf("[%s] Menu was shown before. Dispatch->ShowMenu", $processId));
            $this->messageBus->dispatch(new PlaceOrder($processId));
        }
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onMenuShown(MenuShown $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;

        $this->logger->info(sprintf(
            "[%s] onMenuShown",
            $processId
        ));

        $tableService = $this->servingCustomersRepository->get($processId);
        $tableService->menuWasShown();
        $this->logger->info(sprintf("[%s] Dispatch->ShowMenu", $processId));
        $this->messageBus->dispatch(new PlaceOrder($processId));
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onOrderPlaced(OrderPlaced $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $orderList = $event->orderList;

        $this->logger->info(sprintf(
            "[%s] onOrderPlaced",
            $processId
        ));

        $tableService = $this->servingCustomersRepository->get($processId);

        foreach ($orderList as $order) {
            for ($q=1; $q<=$order['quantity']; $q++) {
                $kitchenOrderId = Uuid::uuid4()->toString();
                $menuId = $order['id'];
                $pizzaSize = $order['size'];
                $tableService->addKitchenOrder($kitchenOrderId, $menuId, $pizzaSize);
                $this->logger->info(sprintf(
                    "[%s:%s] Dispatch->DoPizza %s(%s)",
                    $processId,
                    $kitchenOrderId,
                    $order['id'],
                    $order['size']
                ));
                $this->messageBus->dispatch(new DoPizza($processId, $kitchenOrderId, $order['id'], $order['size']));
            }
        }
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onPizzaDone(PizzaDone $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $kitchenOrderId = $event->kitchenOrderId;

        $tableService = $this->servingCustomersRepository->get($processId);
        $tableService->kitchenOrderDone($kitchenOrderId);
        $this->logger->info(sprintf(
            "[%s:%s] onPizza Done",
            $processId,
            $kitchenOrderId
        ));

        if ($tableService->allOrdersDone()) {
            $this->logger->info(sprintf("[%s] All Pizzas done! All pizzas can be served!", $processId));
            $pizzasToServe = $tableService->getPizzasToServe();
            $this->messageBus->dispatch(new ServePizzas($processId, $pizzasToServe));
        } else {
            $this->logger->info(sprintf("[%s] Not all pizzas ready. We are waiting.", $processId));
        }
        $this->servingCustomersRepository->save($tableService);
    }

    #[AsMessageHandler]
    public function onPizzasServed(PizzasServed $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $this->logger->info(sprintf("[%s] onPizzasServed", $processId));

        $tableService = $this->servingCustomersRepository->get($processId);
        $bill = $tableService->getBill();
        $delayMs = 2000;
        $this->logger->info(sprintf(
            "[%s] Dispatch->ShowBill with delay %d ms",
            $processId,
            $delayMs
        ));
        $this->messageBus->dispatch(new ShowBill($processId, $bill));
    }

    #[AsMessageHandler]
    public function onBillPaid(BillPaid $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $this->logger->info(sprintf("[%s] onBillPaid", $processId));
        $tableService = $this->servingCustomersRepository->get($processId);
        $tableService->finishService();
        $this->servingCustomersRepository->save($tableService);
        $this->logger->info(sprintf("[%s] Dispatch->ThankClient", $processId));
        $this->messageBus->dispatch(new FinishClient($processId));
    }

    #[AsMessageHandler]
    public function onClientFinished(ClientFinished $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $this->logger->info(sprintf(
            "[%s] *** END OF PROCESS ***",
            $event->processId
        ));
    }

    private function isSupportable(string $processId): bool
    {
        return str_starts_with($processId, sprintf("%s:", self::PROCESS_NAME));
    }
}
