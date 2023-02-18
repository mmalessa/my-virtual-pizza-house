<?php

declare(strict_types=1);

namespace App\OrderManager\Domain;

enum TableServiceStatus
{
    case Started;
    case Ended;
}
