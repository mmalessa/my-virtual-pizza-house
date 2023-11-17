<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProcessManager\Application\Message\Kitchen\Command;

use App\ProcessManager\Application\Message\Kitchen\Command\DoPizza;
use PHPUnit\Framework\TestCase;

class DoPizzaTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testDoPizza(string $sagaId, string $kitchenOrderId, string $pizzaId, string $pizzaSize)
    {
        $doPizza = new DoPizza($sagaId, $kitchenOrderId, $pizzaId, $pizzaSize);
        $this->assertEquals($sagaId, $doPizza->processId);
        $this->assertEquals($kitchenOrderId, $doPizza->kitchenOrderId);
        $this->assertEquals($pizzaId, $doPizza->menuId);
        $this->assertEquals($pizzaSize, $doPizza->pizzaSize);
    }

    public static function provideValidData(): array
    {
        return [
            ['8ed543d7-a6ad-4746-8c11-7b27437c0d38', 'b984b3bf-8c9a-49b5-97d2-6702e12dee3c', 'pamat', 'xl'],
            ['c9c18f74-4f08-4cd4-bdaa-702bdc5594b9', '64821cee-659f-485d-9bf3-fd8bab722cda', 'pamat', 'xxl'],
        ];
    }
}
