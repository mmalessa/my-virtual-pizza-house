<?php

declare(strict_types=1);

namespace App\Waiter\Domain\Menu;

use Mmalessa\AutoSerializer\AutoSerializer;

class MenuItem
{
    public function serialize(): array
    {
        return array_merge(
            ['item_type' => (new \ReflectionClass(static::class))->getShortName()],
            AutoSerializer::serialize($this)
        );
    }
}