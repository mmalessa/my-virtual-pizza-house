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
        $timestamp = '2022-01-01 01:02:03';

        $event = new OrderPlaced("T1", [['a' => 'b']], $timestamp);
        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope($event));

        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();

        $handler = new OrderSubscriber($traceableMessageBus, $logger);
        $handler->onOrderPlaced($event);

        $this->assertTrue($logger->hasRecordThatContains("I'm an Order Manager. I will be taking an order for the table: T1 (timestamp: $timestamp)", "info"));
        $this->assertTrue($logger->hasRecordThatContains("Here at some point something will happen with the order...", "info"));
        $this->assertTrue($logger->hasRecordThatContains("I took an order for the table: T1", "info"));
        $this->assertTrue($logger->hasRecordThatContains("At this point - it's over", "info"));
        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 0);
    }
}