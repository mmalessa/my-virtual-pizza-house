<?php

declare(strict_types=1);

namespace App\HeavyWorker\UI\Command;
use App\HeavyWorker\Application\Message\HeavyWorker\Command\DoSomethingHeavy;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:heavy-worker:start',
    description: 'Command for testing long tasks'
)]
class DoSomethingHeavyCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Dispatch DoSomethingSlowlyCommand");
        $this->messageBus->dispatch(new DoSomethingHeavy(25));
        return Command::SUCCESS;
    }
}