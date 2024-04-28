<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\ProcessManager\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.process_manager.start_simple_serving')]
class StartSimpleServing implements ProcessManagerMessageInterface
{
}
