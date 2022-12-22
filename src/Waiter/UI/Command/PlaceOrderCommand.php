<?php

declare(strict_types=1);

namespace App\Waiter\UI\Command;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Domain\Menu\Pizza\Pizza;
use App\Waiter\Domain\Menu\Pizza\PizzaSize;
use App\Waiter\Domain\Menu\Pizza\PizzaThickness;
use App\Waiter\Domain\Menu\Pizza\PizzaType;
use App\Waiter\Domain\Menu\SoftDrink\SoftDrink;
use App\Waiter\Domain\Menu\SoftDrink\SoftDrinkCapacity;
use App\Waiter\Domain\Menu\SoftDrink\SoftDrinkType;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'app:waiter:place-order')]
class PlaceOrderCommand extends Command
{
    public function __construct(
        private readonly MessageBusInterface $messageBus
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("PlaceOrderCommand");

        $order = [];
        $order[] = (new Pizza(
            PizzaType::Amatriciana,
            PizzaSize::Mega,
            PizzaThickness::Thin
        ))->serialize();

        $order[] = (new Pizza(
            PizzaType::Romana,
            PizzaSize::Big,
            PizzaThickness::Thin
        ))->serialize();

        $order[] = (new SoftDrink(
            SoftDrinkType::Lemonade,
            SoftDrinkCapacity::Medium
        ))->serialize();

        $message = new PlaceOrder("T001", $order);
        $this->messageBus->dispatch($message);
        return Command::SUCCESS;
    }
}