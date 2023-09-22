<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use App\Waiter\Application\Message\Waiter\Command\ShowMenu;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use App\Waiter\Application\MessageHandler\PlaceOrderHandler;
use App\Waiter\Application\MessageHandler\ShowMenuHandler;
use App\Waiter\Domain\CommunicatorInterface;
use ColinODell\PsrTestLogger\TestLogger;
use Mmalessa\SomeTools\SomeDelayInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class PlaceOrderHandlerTest extends TestCase
{
    public function testHandler()
    {
        $sagaId = 'c3b9b891-ba30-40cd-b584-5a32b9184b05';
        $orderList = [
            [ 'id' => 'pmghr', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'proma', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'pamat', 'size' => 'xxl', 'quantity' => '1' ],
        ];

        $placeOrder = new PlaceOrder($sagaId);
        $orderPlaced = new OrderPlaced($sagaId, $orderList);

        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope(new \stdClass()));
        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();
        $delay = $this->createMock(SomeDelayInterface::class);

        $handler = new PlaceOrderHandler($traceableMessageBus, $logger, $delay);
        ob_start();
        $handler($placeOrder);
        ob_end_clean();

        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 1);
        $dispatchedMessage = $dispatchedMessages[0]['message'];
        $this->assertTrue($dispatchedMessage::class === OrderPlaced::class);
    }
}
