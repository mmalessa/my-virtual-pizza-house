<?php

declare(strict_types=1);

namespace App\Tests\unit\Waiter\Domain\Menu\Pizza;

use App\Waiter\Domain\Menu\Pizza\Pizza;
use App\Waiter\Domain\Menu\Pizza\PizzaSize;
use App\Waiter\Domain\Menu\Pizza\PizzaThickness;
use App\Waiter\Domain\Menu\Pizza\PizzaType;
use PHPUnit\Framework\TestCase;

class PizzaTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testPizza(PizzaType $type, PizzaSize $size, PizzaThickness $thickness)
    {
        $pizza = new Pizza($type, $size, $thickness);
        $this->assertEquals($type, $pizza->type);
        $this->assertEquals($size, $pizza->size);
        $this->assertEquals($thickness, $pizza->thickness);
    }

    /** @dataProvider provideValidData */
    public function testPizzaSerialize(PizzaType $type, PizzaSize $size, PizzaThickness $thickness)
    {
        $pizza = new Pizza($type, $size, $thickness);
        $expected = [
            'item_type' => 'Pizza',
            'type' => $type->name,
            'size' => $size->name,
            'thickness' => $thickness->name,
        ];
        $this->assertEquals($expected, $pizza->serialize());
    }

    private function provideValidData(): array
    {
        return [
            [PizzaType::Amatriciana, PizzaSize::Big, PizzaThickness::Fat],
            [PizzaType::Romana, PizzaSize::Small, PizzaThickness::Thin],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testPizzaError(?PizzaType $type, ?PizzaSize $size, ?PizzaThickness $thickness)
    {
        $this->expectException(\TypeError::class);
        new Pizza($type, $size, $thickness);
    }

    private function provideInvalidData(): array
    {
        return [
            [null, PizzaSize::Big, PizzaThickness::Fat],
            [PizzaType::Romana, null, PizzaThickness::Thin],
            [PizzaType::Amatriciana, PizzaSize::Small, null],
        ];
    }
}