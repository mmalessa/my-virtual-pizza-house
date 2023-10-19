<?php

declare(strict_types=1);

namespace App\ProcessManager\Application\Process;

use App\ProcessManager\Application\Message\ProcessManager\Command\StartSimpleServing;
use App\ProcessManager\Application\Message\ProcessManager\Event\SimpleServingStarted;
use App\ProcessManager\Application\Message\Waiter\Command\FinishClient;
use App\ProcessManager\Application\Message\Waiter\Command\PlaceOrder;
use App\ProcessManager\Application\Message\Waiter\Command\ShowBill;
use App\ProcessManager\Application\Message\Waiter\Event\BillPaid;
use App\ProcessManager\Application\Message\Waiter\Event\ClientFinished;
use App\ProcessManager\Application\Message\Waiter\Event\OrderPlaced;
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
        $this->logger->info(sprintf("[%s] onSimpleServingStarted", $processId));
        $this->messageBus->dispatch(new PlaceOrder($processId));
    }

    #[AsMessageHandler]
    public function onOrderPlaced(OrderPlaced $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $this->logger->info(sprintf("[%s] onOrderPlaced", $processId));
        $bill = [
            "sum" => [
                'PLN' => 3.50
            ],
            "items" => [],
        ];
        $this->messageBus->dispatch(new ShowBill($processId, $bill));
    }

    #[AsMessageHandler]
    public function onBillPaid(BillPaid $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $processId = $event->processId;
        $this->logger->info(sprintf("[%s] onBillPaid", $processId));
        $this->messageBus->dispatch(new FinishClient($processId));
    }

    #[AsMessageHandler]
    public function onClientFinished(ClientFinished $event): void
    {
        if (!$this->isSupportable($event->processId)) {
            return;
        }
        $this->logger->info(sprintf(
            "[%s] *** END OF PROCESS ***",
            $event->processId
        ));
    }

    private function isSupportable(string $processId): bool
    {
        return str_starts_with($processId, sprintf("%s:", self::PROCESS_NAME));
    }
}
