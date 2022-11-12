<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class PlaceOrderHandler implements MessageHandlerInterface
{
    public function __invoke(PlaceOrder $command)
    {
        echo "PlaceOrderHandler\n";
        print_r($command);
    }
}