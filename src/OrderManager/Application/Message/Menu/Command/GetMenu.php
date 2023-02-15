<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Menu\Command;
use App\OrderManager\Application\Message\OrderManagerMessageInterface;

readonly class GetMenu implements OrderManagerMessageInterface
{
    public function __construct(public string $sagaId)
    {
    }
}