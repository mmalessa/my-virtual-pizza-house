<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\ProcessManagerMessageInterface;
use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\AsExternalMessage;

#[AsExternalMessage(schemaId: 'pizza_house.waiter.bill_paid')]
readonly class BillPaid implements ProcessManagerMessageInterface
{
    public function __construct(public string $processId, public array $sum)
    {
        if (empty($this->sum)) {
            throw new \InvalidArgumentException("Sum cannot be empty");
        }
    }
}
