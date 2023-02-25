<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageSubscriber;

use App\OrderManager\Application\Message\Kitchen\Command\DoPizza;
use App\OrderManager\Application\Message\Kitchen\Event\PizzaDone;
use App\OrderManager\Application\Message\Menu\Command\GetMenu;
use App\OrderManager\Application\Message\Menu\Event\MenuGot;
use App\OrderManager\Application\Message\Waiter\Command\ShowBill;
use App\OrderManager\Application\Message\Waiter\Command\PlaceOrder;
use App\OrderManager\Application\Message\Waiter\Command\ServePizzas;
use App\OrderManager\Application\Message\Waiter\Command\ShowMenu;
use App\OrderManager\Application\Message\Waiter\Command\ThankClient;
use App\OrderManager\Application\Message\Waiter\Event\BillPaid;
use App\OrderManager\Application\Message\Waiter\Event\MenuShown;
use App\OrderManager\Application\Message\Waiter\Event\OrderPlaced;
use App\OrderManager\Application\Message\Waiter\Event\PizzasServed;
use App\OrderManager\Application\Message\Waiter\Event\TableServiceStarted;
use App\OrderManager\Domain\TableService;
use App\OrderManager\Domain\TableServiceRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class TableServiceSubscriber implements MessageSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly TableServiceRepositoryInterface $tableServiceRepository
    )
    {
    }

    /**
     * @codeCoverageIgnore
     */
    public static function getHandledMessages(): iterable
    {
        yield TableServiceStarted::class => ['method' => 'onTableServiceStarted'];
        yield MenuGot::class => ['method' => 'onMenuGot'];
        yield MenuShown::class => ['method' => 'onMenuShown'];
        yield OrderPlaced::class => ['method' => 'onOrderPlaced'];
        yield PizzaDone::class => ['method' => 'onPizzaDone'];
        yield PizzasServed::class => ['method' => 'onPizzasServed'];
        yield BillPaid::class => ['method' => 'onBillPaid'];
    }

    public function onTableServiceStarted(TableServiceStarted $event)
    {
        $sagaId = Uuid::uuid4()->toString();
        $tableId = $event->tableId;

        $tableService = new TableService($sagaId, $tableId);
        $this->tableServiceRepository->save($tableService);

        $this->logger->info(sprintf(
            "[%s] onTableServiceStarted (tableId: %s, sagaId: %s)",
            $sagaId,
            $event->tableId,
            $sagaId
        ));
        $this->messageBus->dispatch(new GetMenu($sagaId));
    }

    public function onMenuGot(MenuGot $event)
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

    public function onMenuShown(MenuShown $event)
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


    public function onOrderPlaced(OrderPlaced $event)
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

    public function onPizzaDone(PizzaDone $event)
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

    public function onPizzasServed(PizzasServed $event)
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

    public function onBillPaid(BillPaid $event)
    {
        $sagaId = $event->sagaId;
        $this->logger->info(sprintf("[%s] onBillPaid", $sagaId));
        $tableService = $this->tableServiceRepository->get($sagaId);
        $tableService->finishService();
        $this->tableServiceRepository->save($tableService);
        $this->logger->info(sprintf("[%s] Dispatch->ThankClient", $sagaId));
        $this->messageBus->dispatch(new ThankClient($sagaId));
    }
}