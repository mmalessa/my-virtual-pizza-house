<?php

declare(strict_types=1);

namespace App\Menu\Application\Message\Menu\Event;

use App\Menu\Application\Message\MenuMessageInterface;

readonly class MenuGot  implements MenuMessageInterface
{
    public function __construct(public string $processId, public array $menu)
    {
    }
}
