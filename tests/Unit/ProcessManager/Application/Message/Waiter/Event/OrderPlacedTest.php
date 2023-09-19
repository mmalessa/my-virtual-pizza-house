<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProcessManager\Application\Message\Waiter\Event;

use App\ProcessManager\Application\Message\Waiter\Event\OrderPlaced;
use PHPUnit\Framework\TestCase;

class OrderPlacedTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testOrderPlaced(string $sagaId, array $orderList)
    {
        $placeOrder = new OrderPlaced($sagaId, $orderList);
        $this->assertEquals($sagaId, $placeOrder->sagaId);
        $this->assertEquals($orderList, $placeOrder->orderList);
    }

    public static function provideValidData(): array
    {
        return [
            ['b3816513-6108-4b2c-a7f3-c3ad6f11bb02', [['a' => 'b', 'c' => 'd']]],
            ['c3b9b891-ba30-40cd-b584-5a32b9184b05', [['a' => 'b']]],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testOrderPlacedError(string $sagaId, array $orderList)
    {
        $this->expectException(\InvalidArgumentException::class);
        new OrderPlaced($sagaId, $orderList);
    }

    public static function provideInvalidData(): array
    {
        return [
            ['', [['a' => 'b']]],
            ['x', []],
        ];
    }
}
