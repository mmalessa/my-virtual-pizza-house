<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain\ServingCustomers;

enum ServingCustomersStatus
{
    case Started;
    case Ended;
}
