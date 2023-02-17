<?php

declare(strict_types=1);

namespace App\Tests\Unit\Menu\Application\MessageHandler;

use App\Menu\Application\Message\Menu\Command\GetMenu;
use App\Menu\Application\Message\Menu\Event\MenuGot;
use App\Menu\Application\MessageHandler\GetMenuHandler;
use App\Menu\Domain\Query\GetMenuQueryInterface;
use ColinODell\PsrTestLogger\TestLogger;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class GetMenuHandlerTest extends TestCase
{
    public function testHandler()
    {
        $sagaId = 'c3b9b891-ba30-40cd-b584-5a32b9184b05';
        $menu = ['some', 'fake', 'menu'];

        $getMenu = new GetMenu($sagaId);
        $menuGot = new MenuGot($sagaId, $menu);
        $messageBus = $this->getMockBuilder(MessageBusInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $messageBus->method('dispatch')->willReturn(new Envelope(new \stdClass()));
        $traceableMessageBus = new TraceableMessageBus($messageBus);
        $logger = new TestLogger();

        $getMenuQuery = $this->getMockBuilder(GetMenuQueryInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
        $getMenuQuery->method('getMenu')->willReturn($menu);
        $handler = new GetMenuHandler($traceableMessageBus, $logger, $getMenuQuery);
        $handler($getMenu);

        $dispatchedMessages = $traceableMessageBus->getDispatchedMessages();
        $this->assertTrue(count($dispatchedMessages) === 1);
        $dispatchedMessage = $dispatchedMessages[0]['message'];
        $this->assertTrue($dispatchedMessage::class === MenuGot::class);

        $logger->hasRecordThatContains("[c3b9b891-ba30-40cd-b584-5a32b9184b05] GetMenu received", "info");
        $logger->hasRecordThatContains("[c3b9b891-ba30-40cd-b584-5a32b9184b05] GotMenu dispatched (3 items)", "info");
    }
}