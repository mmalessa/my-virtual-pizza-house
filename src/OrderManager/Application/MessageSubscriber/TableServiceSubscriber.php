<?php

declare(strict_types=1);

namespace App\OrderManager\Application\MessageSubscriber;

use App\OrderManager\Application\Message\Kitchen\Command\DoPizza;
use App\OrderManager\Application\Message\Kitchen\Event\PizzaDone;
use App\OrderManager\Application\Message\Menu\Command\GetMenu;
use App\OrderManager\Application\Message\Menu\Event\MenuGot;
use App\OrderManager\Application\Message\Waiter\Command\ShowMenu;
use App\OrderManager\Application\Message\Waiter\Event\OrderPlaced;
use App\OrderManager\Application\Message\Waiter\Event\TableServiceStarted;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TableServiceSubscriber implements MessageSubscriberInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
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
        yield OrderPlaced::class => ['method' => 'onOrderPlaced'];
        yield PizzaDone::class => ['method' => 'onPizzaDone'];
    }

    public function onTableServiceStarted(TableServiceStarted $event)
    {
        $sagaId = Uuid::uuid4()->toString();
        //TODO persist TableService($sagaId)

        $this->logger->info(sprintf(
            "[%s] TableServiceStarted (tableId: %s, sagaId: %s)",
            $sagaId,
            $event->tableId,
            $sagaId
        ));
        $this->messageBus->dispatch(new GetMenu($sagaId));
    }

    public function onMenuGot(MenuGot $event)
    {
        $this->logger->info(sprintf(
            "[%s] MenuGot",
            $event->sagaId
        ));
        $this->messageBus->dispatch(new ShowMenu($event->sagaId, $event->menu));
    }

    public function onOrderPlaced(OrderPlaced $event)
    {
        $sagaId = $event->sagaId;
        $orderList = $event->orderList;

        $this->logger->info(sprintf(
            "[%s] OrderPlaced",
            $sagaId
        ));

        foreach ($orderList as $order) {
            for ($q=1; $q<=$order['quantity']; $q++) {
                $kitchenOrderId = Uuid::uuid4()->toString();
                //TODO -> persist $kitchenOrderId
                $this->logger->info(sprintf(
                    "[%s:%s] DoPizza %s(%s)",
                    $sagaId,
                    $kitchenOrderId,
                    $order['id'],
                    $order['size']
                ));
                $this->messageBus->dispatch(new DoPizza($sagaId, $kitchenOrderId, $order['id'], $order['size']));
            }
        }
    }

    public function onPizzaDone(PizzaDone $event)
    {
        $sagaId = $event->sagaId;
        $kitchenOrderId = $event->kitchenOrderId;

        $this->logger->info(sprintf(
            "[%s:%s] Pizza Done",
            $sagaId,
            $kitchenOrderId
        ));

        //TODO - check if all pizzas done
        //TODO - if all done -> dispatch ServePizzas()

    }

}