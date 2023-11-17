<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use PHPUnit\Framework\TestCase;

class OrderPlacedTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testOrderPlaced(string $sagaId, array $orderList)
    {
        $placeOrder = new OrderPlaced($sagaId, $orderList);
        $this->assertEquals($sagaId, $placeOrder->processId);
        $this->assertEquals($orderList, $placeOrder->orderList);
    }

    public static function provideValidData(): array
    {
        return [
            ['b3816513-6108-4b2c-a7f3-c3ad6f11bb02', [['a' => 'b', 'c' => 'd']]],
            ['64821cee-659f-485d-9bf3-fd8bab722cda', [['a' => 'b']]],
        ];
    }
}
