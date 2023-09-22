<?php

declare(strict_types=1);

namespace App\ProcessManager\UI\ConsoleCommand;

use App\ProcessManager\Application\Message\ProcessManager\Command\Start;
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
        $message = new Start('SomeTableId');
        $this->messageBus->dispatch($message);
        return Command::SUCCESS;
    }
}
