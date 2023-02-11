<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use App\Waiter\Application\MessageHandler\PlaceOrderHandler;
use ColinODell\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class PlaceOrderHandlerTest extends TestCase
{
    public function testHandler()
    {
        $command = new PlaceOrder("T1", [['a' => 'b']]);
        $event = new OrderPlaced("T1", [['a' => 'b']], '2022-01-01 01:02:03');

        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope($event));
        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();
        $handler = new PlaceOrderHandler($traceableMessageBus, $logger);
        $handler($command);

        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue($logger->hasRecordThatContains("Hi table T1, I'm a waiter. I want to accept your order.", "info"));
        $this->assertTrue($logger->hasRecordThatContains("Thank you table T1. I took the order.", "info"));
        $this->assertTrue(count($dispatchedMessages) === 1);
        $dispatchedMessage = $dispatchedMessages[0]['message'];
        $this->assertTrue($dispatchedMessage::class === OrderPlaced::class);
        $this->assertEquals('T1', $dispatchedMessage->tableId);
        $this->assertEquals([['a' => 'b']], $dispatchedMessage->order);
    }
}