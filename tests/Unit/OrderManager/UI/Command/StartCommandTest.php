<?php

declare(strict_types=1);

namespace App\Tests\Unit\OrderManager\UI\Command;

use App\OrderManager\Application\Message\OrderManager\Command\Start;
use App\OrderManager\UI\ConsoleCommand\StartCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class StartCommandTest extends KernelTestCase
{
    public function testExecute()
    {
        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->method("dispatch")->willReturn(new Envelope(new \stdClass()));
        $traceableMessageBus = new TraceableMessageBus($messageBus);

        $application = new Application();
        $application->add(new StartCommand($traceableMessageBus));

        $command = $application->find('app:order-manager:start');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'tableid' => 'T1'
        ]);

//        $this->assertEquals("[Command] Start table service (tableId: T1)\n", $commandTester->getDisplay(true));
        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertEquals(1, count($dispatchedMessages));
        $this->assertEquals(Start::class, get_class($dispatchedMessages[0]['message']));
        $this->assertEquals(Command::SUCCESS, $commandTester->getStatusCode());
    }
}