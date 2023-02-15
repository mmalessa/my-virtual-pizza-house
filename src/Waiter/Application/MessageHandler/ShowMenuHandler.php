<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ShowMenu;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use App\Waiter\Domain\CommunicatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ShowMenuHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly LoggerInterface $logger,
        private readonly CommunicatorInterface $communicator
    )
    {
    }

    public function __invoke(ShowMenu $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] ShowMenu",
            $sagaId
        ));
        $this->communicator->showMenu($command->menu);
        $this->logger->info(sprintf(
            "[%s] This is where communication with the client should take place",
            $sagaId
        ));
        $orderList = [
            [ 'id' => 'pmghr', 'size' => 'xl', 'quantity' => '2' ],
            [ 'id' => 'proma', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'pamat', 'size' => 'xxl', 'quantity' => '2' ],
        ];
        $this->logger->info(sprintf(
            "[%s] Let's simplify it. A customer orders: %s",
            $sagaId,
            $this->getNiceOrder($orderList)
        ));
        $this->messageBus->dispatch(new OrderPlaced($sagaId, $orderList));
    }

    private function getNiceOrder(array $orderList): string
    {
        $niceItems = [];
        foreach ($orderList as $item) {
            $niceItems[] = sprintf("%s(%s) x %s", $item['id'], $item['size'], $item['quantity']);
        }
        return implode(", ", $niceItems);
    }

}