<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\Waiter\Event\OrderPlaced;
use PHPUnit\Framework\TestCase;

class OrderPlacedTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testOrderPlaced(string $tableId, array $order, string $timestamp)
    {
        $placeOrder = new OrderPlaced($tableId, $order, $timestamp);
        $this->assertEquals($tableId, $placeOrder->tableId);
        $this->assertEquals($order, $placeOrder->order);
    }

    private function provideValidData(): array
    {
        return [
            ['Table001', [['a' => 'b', 'c' => 'd']], '2022-01-01 10:11:12'],
            ['Something', [['a' => 'b']], '2023-12-31 01:02:03'],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testOrderPlacedError(string $tableId, array $order, string $timestamp)
    {
        $this->expectException(\InvalidArgumentException::class);
        new OrderPlaced($tableId, $order, $timestamp);
    }

    private function provideInvalidData(): array
    {
        return [
            ['', [['a' => 'b']], '2022-01-01 10:11:12'],
            ['x', [], '2022-01-01 01:02:03'],
            ['bbbbb', [['a' => 'b']], ''],
            ['bbbbb', [['a' => 'b']], '2022-02-31 01:01:01'],
        ];
    }
}