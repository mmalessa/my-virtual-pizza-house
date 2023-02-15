<?php

declare(strict_types=1);

namespace App\OrderManager\Application\Message\Menu\Event;

use App\OrderManager\Application\Message\OrderManagerMessageInterface;

readonly class MenuGot implements OrderManagerMessageInterface
{
    public function __construct(public string $sagaId, public array $menu)
    {
    }
}