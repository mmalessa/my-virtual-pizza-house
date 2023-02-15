<?php

declare(strict_types=1);

namespace App\Waiter\Domain;

interface CommunicatorInterface
{
    public function showMenu(array $menu);
}