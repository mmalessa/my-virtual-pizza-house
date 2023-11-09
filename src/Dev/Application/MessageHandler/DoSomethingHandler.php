<?php

namespace App\Dev\Application\MessageHandler;

use App\Dev\Application\Message\DoSomething;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class DoSomethingHandler
{
    public function __invoke(DoSomething $message)
    {
        echo "DoSomethingHandler here!\n";
    }
}