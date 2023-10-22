<?php

declare(strict_types=1);

namespace App\ProcessManager\UI\ConsoleCommand\Dev;

use App\ProcessManager\Domain\SimpleServing\SimpleServing;
use App\ProcessManager\Domain\SimpleServing\SimpleServingId;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:event-sourcing:store')]
class EventSourcingStoreCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
        private readonly EventSourcedAggregateRootRepository $simpleServingRepository
    )
    {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = SimpleServingId::fromString('SomeId');
        $aggregate = SimpleServing::initiate($id);
        $aggregate->placeOrder(['Pizza Italiana', 'Pizza Romana']);
        $aggregate->placeOrder(['Water', 'Tea']);

        $this->simpleServingRepository->persist($aggregate);

        return Command::SUCCESS;
    }
}
