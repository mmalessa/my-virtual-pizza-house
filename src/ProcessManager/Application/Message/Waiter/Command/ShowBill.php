<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Ramsey\Uuid\Uuid;

readonly class ShowBill implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $bill)
    {
        if (empty($this->bill)) {
            throw new \InvalidArgumentException("Bill cannot be empty");
        }
    }
}
