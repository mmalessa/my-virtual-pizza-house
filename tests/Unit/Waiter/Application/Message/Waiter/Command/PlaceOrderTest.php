<?php

declare(strict_types=1);

namespace App\Tests\unit\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\Waiter\Command\PlaceOrder;
use PHPUnit\Framework\TestCase;

class PlaceOrderTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testPlaceOrder(string $tableId, array $order)
    {
        $placeOrder = new PlaceOrder($tableId, $order);
        $this->assertEquals($tableId, $placeOrder->tableId);
        $this->assertEquals($order, $placeOrder->order);
    }

    private function provideValidData(): array
    {
        return [
            ['Table001', [['a' => 'b', 'c' => 'd']]],
            ['Something', [['a' => 'b']]],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testPlaceOrderError(string $tableId, array $order)
    {
        $this->expectException(\InvalidArgumentException::class);
        new PlaceOrder($tableId, $order);
    }

    private function provideInvalidData(): array
    {
        return [
            ['', [['a' => 'b']]],
            ['x', []],
        ];
    }
}