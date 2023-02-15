<?php

declare(strict_types=1);

namespace App\Tests\Unit\Kitchen\Application\Message\Kitchen\Command;

use App\Kitchen\Application\Message\Kitchen\Command\DoPizza;
use PHPUnit\Framework\TestCase;

class DoPizzaTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testDoPizza(string $sagaId, string $kitchenOrderId, string $pizzaId, string $pizzaSize)
    {
        $doPizza = new DoPizza($sagaId, $kitchenOrderId, $pizzaId, $pizzaSize);
        $this->assertEquals($sagaId, $doPizza->sagaId);
        $this->assertEquals($kitchenOrderId, $doPizza->kitchenOrderId);
        $this->assertEquals($pizzaId, $doPizza->pizzaId);
        $this->assertEquals($pizzaSize, $doPizza->pizzaSize);
    }

    private function provideValidData(): array
    {
        return [
            ['sagaId', 'kitchenOrderId', 'pamat', 'xl'],
            ['sagaId', 'kitchenOrderId', 'pamat', 'xxl'],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testDoPizzaError(string $sagaId, string $kitchenOrderId, string $pizzaId, string $pizzaSize)
    {
        $this->expectException(\InvalidArgumentException::class);
        new DoPizza($sagaId, $kitchenOrderId, $pizzaId, $pizzaSize);
    }

    private function provideInvalidData(): array
    {
        return [
            ['', 'kitchenOrderId', 'pamat', 'xl'],
            ['sagaId', '', 'pamat', 'xxl'],
            ['sagaId', 'kitchenOrderId', '', 'xxl'],
            ['sagaId', 'kitchenOrderId', 'pamat', ''],
        ];
    }
}