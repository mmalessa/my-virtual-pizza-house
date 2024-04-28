<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.waiter.show_bill')]
readonly class ShowBill implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $bill)
    {
        if (empty($this->bill)) {
            throw new \InvalidArgumentException("Bill cannot be empty");
        }
    }
}
