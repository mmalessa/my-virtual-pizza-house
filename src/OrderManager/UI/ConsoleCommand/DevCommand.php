<?php

declare(strict_types=1);

namespace App\OrderManager\UI\ConsoleCommand;

use App\OrderManager\Application\Message\Waiter\Command\StartTableService;
use App\OrderManager\Domain\TableId;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:order-manager:dev'
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
        $tableId = new TableId('SomeId');
        $startTableService = new StartTableService($tableId);
        $this->messageBus->dispatch($startTableService);
        return Command::SUCCESS;
    }
}