<?php

declare(strict_types=1);

namespace App\ProcessManager\UI\ConsoleCommand\Dev;

use App\ProcessManager\Application\Message\ProcessManager\Command\StartServingCustomers;
use App\ProcessManager\Domain\SimpleServing\SimpleServing;
use App\ProcessManager\Domain\SimpleServing\SimpleServingId;
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
        $id = SimpleServingId::fromString('SomeId');
        $aggregate = SimpleServing::initiate($id);
        $order = [
            'Pizza Italiana', 'Pizza Romana'
        ];
        $aggregate->placeOrder($order);

        print_r($aggregate);

        return Command::SUCCESS;
    }
}
