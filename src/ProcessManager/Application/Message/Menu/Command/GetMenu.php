<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Menu\Command;
use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.menu.get_menu')]
readonly class GetMenu implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
