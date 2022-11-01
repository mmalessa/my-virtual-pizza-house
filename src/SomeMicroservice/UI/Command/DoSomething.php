<?php

namespace App\SomeMicroservice\UI\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// https://symfony.com/doc/current/console.html

#[AsCommand(name: 'app:some-microservice:do-something')]
class DoSomething extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Do something");
        return Command::SUCCESS;
    }
}