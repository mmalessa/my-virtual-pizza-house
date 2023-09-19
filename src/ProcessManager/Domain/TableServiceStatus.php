<?php

declare(strict_types=1);

namespace App\ProcessManager\Domain;

enum TableServiceStatus
{
    case Started;
    case Ended;
}
