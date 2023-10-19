<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;

class ServePizzas implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $pizzas)
    {
        if (empty($this->pizzas)) {
            throw new \InvalidArgumentException("Pizzas cannot be empty");
        }
    }
}
