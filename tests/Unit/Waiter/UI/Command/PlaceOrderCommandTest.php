<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\UI\Command;

use App\Waiter\UI\Command\PlaceOrderCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class PlaceOrderCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->method("dispatch")->willReturn(new Envelope(new \stdClass()));

        $application = new Application();
        $application->add(new PlaceOrderCommand($messageBus));

        $command = $application->find('app:waiter:place-order');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }
}