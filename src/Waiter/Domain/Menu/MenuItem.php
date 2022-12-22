<?php

declare(strict_types=1);

namespace App\Waiter\Domain\Menu;

use Mmalessa\AutoSerializer\AutoSerializer;

abstract class MenuItem
{
    public function serialize(): array
    {
        return AutoSerializer::serialize($this);
    }
}