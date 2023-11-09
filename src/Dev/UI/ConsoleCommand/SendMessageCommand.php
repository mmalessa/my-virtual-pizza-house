<?php

namespace App\Dev\UI\ConsoleCommand;

use App\Dev\Application\Message\DoSomething;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:dev:send-message')]
class SendMessageCommand extends Command
{
    public function __construct(
        private MessageBusInterface $outboxMessageBus,
        private MessageBusInterface $externalMessageBus
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Elo, elo");
        $message = new DoSomething();
        $this->outboxMessageBus->dispatch($message);
        $this->externalMessageBus->dispatch($message);
        return Command::SUCCESS;
    }
}