<?php

namespace App\Waiter\UI\Command;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:waiter:place-order')]
class PlaceOrderCommand extends Command
{
    public function __construct(
        private MessageBusInterface $messageBus
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("PlaceOrderCommand");

        $order = [];
        $message = new PlaceOrder("T001", $order);
        $this->messageBus->dispatch($message);
        return Command::SUCCESS;
    }
}