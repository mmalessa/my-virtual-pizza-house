<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\MessageHandler;

use App\Waiter\Application\Message\Waiter\Command\ShowMenu;
use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use App\Waiter\Application\MessageHandler\ShowMenuHandler;
use App\Waiter\Domain\CommunicatorInterface;
use ColinODell\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class ShowMenuHandlerTest extends TestCase
{
    public function testHandler()
    {
        $sagaId = 'c3b9b891-ba30-40cd-b584-5a32b9184b05';
        $menu = [
            [
                'pmghr' => [
                    'name' => 'Pizza Margherita',
                    'size' => [
                        'xl' => ['price' => 14, 'currency' => 'EUR'],
                        'xxl' => ['price' => 25, 'currency' => 'EUR'],
                    ]
                ]
            ]
        ];
        $orderList = [
            [ 'id' => 'pmghr', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'proma', 'size' => 'xl', 'quantity' => '1' ],
            [ 'id' => 'pamat', 'size' => 'xxl', 'quantity' => '1' ],
        ];

        $showMenu = new ShowMenu($sagaId, $menu);
        $orderPlaced = new OrderPlaced($sagaId, $orderList);

        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope(new \stdClass()));
        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();

        $communicator = $this->getMockBuilder(CommunicatorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $communicator->method('showMenu')->willReturn("Some text");

        $handler = new ShowMenuHandler($traceableMessageBus, $logger, $communicator);
        $handler($showMenu);

        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 1);
        $dispatchedMessage = $dispatchedMessages[0]['message'];
        $this->assertTrue($dispatchedMessage::class === OrderPlaced::class);
    }
}