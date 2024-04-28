<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.waiter.order_placed')]
readonly class OrderPlaced implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $orderList)
    {
        if (empty($this->orderList)) {
            throw new \InvalidArgumentException("OrderList cannot be empty");
        }
    }
}
