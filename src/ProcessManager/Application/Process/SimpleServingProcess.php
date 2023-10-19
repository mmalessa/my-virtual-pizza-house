<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Process;

use App\ProcessManager\Application\Message\ProcessManager\Command\StartSimpleServing;
use App\ProcessManager\Application\Message\ProcessManager\Event\SimpleServingStarted;
use App\ProcessManager\Domain\ServingCustomers\ServingCustomersRepositoryInterface;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

class SimpleServingProcess
{
    private const PROCESS_NAME = "SimpleServing";

    public function __construct(
        protected readonly MessageBusInterface $messageBus,
        protected readonly LoggerInterface $logger,
        protected readonly ServingCustomersRepositoryInterface $servingCustomersRepository
    )
    {
    }

    #[AsMessageHandler]
    public function startProcess(StartSimpleServing $command): void
    {
        $processId = sprintf(
            "%s:%s",
            self::PROCESS_NAME,
            Uuid::uuid6()->toString()
        );
        $this->logger->info("We start simple serving");
        $this->messageBus->dispatch(new SimpleServingStarted($processId));
    }

    #[AsMessageHandler]
    public function onSimpleServingStarted(SimpleServingStarted $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $this->logger->info(sprintf(
            "[%s] onSimpleServingStarted",
            $processId,
        ));
    }

    private function isSupportable(string $processId): bool
    {
        return str_starts_with($processId, sprintf("%s:", self::PROCESS_NAME));
    }
}
