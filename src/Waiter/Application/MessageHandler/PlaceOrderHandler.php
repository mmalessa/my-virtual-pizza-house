<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PlaceOrderHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(PlaceOrder $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] PlaceOrder",
            $sagaId
        ));
        $this->logger->info(sprintf(
            "[%s] This is where communication with the client should take place. Let's simplify it.",
            $sagaId
        ));
        $orderList = [
            [ 'id' => 'pmghr', 'size' => 'xl', 'quantity' => '2' ],
            [ 'id' => 'proma', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'pamat', 'size' => 'xxl', 'quantity' => '2' ],
        ];
        echo "------- Customer order -------\n";
        echo $this->getNiceOrder($orderList) . PHP_EOL;
        echo "------------------------------\n";
        $this->messageBus->dispatch(new OrderPlaced($sagaId, $orderList));
        $this->logger->info(sprintf("[%s] OrderPlaced dispatched", $sagaId));
    }


    private function getNiceOrder(array $orderList): string
    {
        $niceItems = [];
        foreach ($orderList as $item) {
            $niceItems[] = sprintf("%s(%s) x %s", $item['id'], $item['size'], $item['quantity']);
        }
        return implode("\n", $niceItems);
    }
}