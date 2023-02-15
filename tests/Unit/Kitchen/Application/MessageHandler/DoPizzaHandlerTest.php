<?php

declare(strict_types=1);

namespace App\Tests\Unit\Kitchen\Application\MessageHandler;

use App\Kitchen\Application\Message\Kitchen\Command\DoPizza;
use App\Kitchen\Application\Message\Kitchen\Event\PizzaDone;
use App\Kitchen\Application\MessageHandler\DoPizzaHandler;
use ColinODell\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class DoPizzaHandlerTest extends TestCase
{
    public function testHandler()
    {
        $sagaId = 'SagaId';
        $kitchenOrderId = 'KitchenOrderId';
        $pizzaId = 'PizzaId';
        $pizzaSize = 'PizzaSize';

        $doPizza = new DoPizza($sagaId, $kitchenOrderId, $pizzaId, $pizzaSize);
        $pizzaDone = new PizzaDone($sagaId, $kitchenOrderId);

        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope(new \stdClass()));
        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();
        $handler = new DoPizzaHandler($traceableMessageBus, $logger);
        $handler($doPizza);

        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 1);
        $dispatchedMessage = $dispatchedMessages[0]['message'];
        $this->assertTrue($dispatchedMessage::class === PizzaDone::class);
    }
}