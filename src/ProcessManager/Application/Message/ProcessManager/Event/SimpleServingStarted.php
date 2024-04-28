<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.process_manager.simple_serving_started')]
class SimpleServingStarted implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
