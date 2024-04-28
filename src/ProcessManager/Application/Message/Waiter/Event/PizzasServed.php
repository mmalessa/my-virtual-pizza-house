<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.waiter.pizza_served')]
class PizzasServed implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
