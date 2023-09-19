<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProcessManager\Application\Message\Kitchen\Event;

use App\ProcessManager\Application\Message\Kitchen\Event\PizzaDone;
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

    public static function provideValidData(): array
    {
        return [
            ['64821cee-659f-485d-9bf3-fd8bab722cda', '8333b390-b7e8-468a-a0a9-d71a1aff3982'],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testPizzaDoneError(string $sagaId, string $kitchenOrderId)
    {
        $this->expectException(\InvalidArgumentException::class);
        new PizzaDone($sagaId, $kitchenOrderId);
    }

    public static function provideInvalidData(): array
    {
        return [
            ['', 'kitchenOrderId'],
            ['sagaId', '']
        ];
    }
}
