<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\SimpleServing;

use EventSauce\EventSourcing\AggregateRootId;

class SimpleServingId implements AggregateRootId
{
    private function __construct(
        private string $id
    ) {
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $aggregateRootId): static
    {
        return new static($aggregateRootId);
    }
}
