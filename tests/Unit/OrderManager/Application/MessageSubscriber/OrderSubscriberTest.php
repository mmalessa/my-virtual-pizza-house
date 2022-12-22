<?php

declare(strict_types=1);

namespace App\Tests\Unit\OrderManager\Application\MessageSubscriber;

use App\OrderManager\Application\Message\Waiter\Event\OrderPlaced;
use App\OrderManager\Application\MessageSubscriber\OrderSubscriber;
use ColinODell\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class OrderSubscriberTest extends TestCase
{
    public function testOnOrderPlaced()
    {
        $event = new OrderPlaced("T1", [['a' => 'b']], '2022-01-01 01:02:03');
        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope($event));

        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();

        $handler = new OrderSubscriber($traceableMessageBus, $logger);
        $handler->onOrderPlaced($event);

        $this->assertTrue($logger->hasRecordThatContains("onOrderPlaced, Table: T1, Timestamp: 2022-01-01 01:02:03", "info"));
        $this->assertTrue($logger->hasRecordThatContains("onOrderPlaced DONE", "info"));
        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 0);
    }
}