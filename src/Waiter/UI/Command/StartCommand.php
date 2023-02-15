<?php

declare(strict_types=1);

namespace App\Waiter\UI\Command;

use App\Waiter\Application\Message\Waiter\Event\TableServiceStarted;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:waiter:start',
    description: 'Start table service'
)]
class StartCommand extends Command
{
    public function configure()
    {
        $this
            ->addArgument('tableid', InputArgument::REQUIRED, 'Table ID')
        ;
    }

    public function __construct(
        private readonly MessageBusInterface $messageBus
    )
    {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tableId = $input->getArgument('tableid');
        $output->writeln(sprintf(
            "[Command] Start table service (tableId: %s)",
            $tableId
        ));
        $this->messageBus->dispatch(new TableServiceStarted($tableId));
        return Command::SUCCESS;
    }
}