<?php

declare(strict_types=1);

namespace App\ProcessManager\UI\ConsoleCommand;

use App\ProcessManager\Application\Message\ProcessManager\Command\Start;
use App\ProcessManager\Domain\TableId;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:order-manager:start'
)]
class StartCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    )
    {
        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addArgument('tableid', InputArgument::REQUIRED, 'Table ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tableId = new Tableid($input->getArgument('tableid'));
        $startTableService = new Start($tableId);
        $this->messageBus->dispatch($startTableService);
        return Command::SUCCESS;
    }
}
