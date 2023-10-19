<?php

declare(strict_types=1);

namespace App\Menu\Application\Message\Menu\Command;

use App\Menu\Application\Message\MenuMessageInterface;

readonly class GetMenu implements MenuMessageInterface
{
    public function __construct(public string $processId)
    {
    }
}
