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
        $this->assertEquals($pizzaId, $doPizza->menuId);
        $this->assertEquals($pizzaSize, $doPizza->pizzaSize);
    }

    private function provideValidData(): array
    {
        return [
            ['8ed543d7-a6ad-4746-8c11-7b27437c0d38', 'b984b3bf-8c9a-49b5-97d2-6702e12dee3c', 'pamat', 'xl'],
            ['c9c18f74-4f08-4cd4-bdaa-702bdc5594b9', '64821cee-659f-485d-9bf3-fd8bab722cda', 'pamat', 'xxl'],
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
            ['c9c18f74-4f08-4cd4-bdaa-702bdc5594b9', '', 'pamat', 'xxl'],
            ['64821cee-659f-485d-9bf3-fd8bab722cda', 'kitchenOrderId', '', 'xxl'],
            ['b984b3bf-8c9a-49b5-97d2-6702e12dee3c', 'b984b3bf-8c9a-49b5-97d2-6702e12dee3c', 'pamat', ''],
        ];
    }
}