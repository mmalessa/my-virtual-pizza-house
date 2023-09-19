<?php

declare(strict_types=1);

namespace App\ProcessManager\UI\ConsoleCommand;

use App\ProcessManager\Application\Message\Waiter\Command\StartTableService;
use App\ProcessManager\Application\Saga\SagaId;
use App\ProcessManager\Domain\TableId;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:process-manager:dev'
)]
class DevCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    )
    {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sagaId = new SagaId(Uuid::uuid4()->toString());
        $tableId = new TableId('SomeId');
        $message = new StartTableService($sagaId, $tableId);
        $this->messageBus->dispatch($message);
        return Command::SUCCESS;
    }
}
