<?php

declare(strict_types=1);

namespace App\Menu\Domain\Query;
interface GetMenuQueryInterface
{
    public function getMenu(): array;
}