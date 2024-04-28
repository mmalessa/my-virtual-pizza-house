<?php

declare(strict_types=1);

namespace App\TaggingSandbox\UI\Console;

use Mmalessa\MessengerAddonsBundle\ExternalMessageMapper\ExternalMessageMapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:sandbox:mapper')]
class MapperTestCommand extends Command
{
    public function __construct(
        private ExternalMessageMapper $classMapper
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Some Sand');
        print_r($this->classMapper->devSerialize());
        return Command::SUCCESS;
    }
}
