<?php

declare(strict_types=1);

namespace App\Tests\Unit\Kitchen\Application\Message\Kitchen\Event;

use App\Kitchen\Application\Message\Kitchen\Event\PizzaDone;
use PHPUnit\Framework\TestCase;

class PizzaDoneTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testPizzaDone(string $sagaId, string $kitchenOrderId)
    {
        $pizzaDone = new PizzaDone($sagaId, $kitchenOrderId);
        $this->assertEquals($sagaId, $pizzaDone->sagaId);
        $this->assertEquals($kitchenOrderId, $pizzaDone->kitchenOrderId);
    }

    private function provideValidData(): array
    {
        return [
            ['sagaId', 'kitchenOrderId'],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testPizzaDoneError(string $sagaId, string $kitchenOrderId)
    {
        $this->expectException(\InvalidArgumentException::class);
        new PizzaDone($sagaId, $kitchenOrderId);
    }

    private function provideInvalidData(): array
    {
        return [
            ['', 'kitchenOrderId'],
            ['sagaId', '']
        ];
    }
}