<?php

declare(strict_types=1);

namespace App\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ThankClient;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ThankClientHandler
{
    public function __construct(
        private readonly LoggerInterface $logger
    )
    {
    }

    public function __invoke(ThankClient $command)
    {
        $sagaId = $command->sagaId;
        $this->logger->info(sprintf(
            "[%s] ThankClient",
            $sagaId
        ));
        echo "---- Thank you very much for staying with us. Welcome again! ----\n";
    }
}