<?php

declare(strict_types=1);

namespace App\Waiter\Domain;

class TableId implements \Stringable
{
    public function __construct(private string $tableId)
    {
        if (empty($this->tableId)) {
            throw new \InvalidArgumentException("TableId cannot be empty");
        }
    }

    public function __toString(): string
    {
        return $this->tableId;
    }
}