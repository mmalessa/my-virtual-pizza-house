<?php

declare(strict_types=1);

namespace App\Tests\unit\Waiter\Domain\Menu\SoftDrink;

use App\Waiter\Domain\Menu\SoftDrink\SoftDrink;
use App\Waiter\Domain\Menu\SoftDrink\SoftDrinkCapacity;
use App\Waiter\Domain\Menu\SoftDrink\SoftDrinkType;
use PHPUnit\Framework\TestCase;

class SoftDrinkTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testSoftDrink(SoftDrinkType $type, SoftDrinkCapacity $capacity)
    {
        $softDrink = new SoftDrink($type, $capacity);
        $this->assertEquals($type, $softDrink->type);
        $this->assertEquals($capacity, $softDrink->capacity);
    }

    /** @dataProvider provideValidData */
    public function testSoftDrinkSerialize(SoftDrinkType $type, SoftDrinkCapacity $capacity)
    {
        $softDrink = new SoftDrink($type, $capacity);
        $expected = [
            'item_type' => 'SoftDrink',
            'type' => $type->name,
            'capacity' => $capacity->name,
        ];
        $this->assertEquals($expected, $softDrink->serialize());
    }

    private function provideValidData(): array
    {
        return [
            [SoftDrinkType::Lemonade, SoftDrinkCapacity::Small],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testSoftDrinkError(?SoftDrinkType $type, ?SoftDrinkCapacity $capacity)
    {
        $this->expectException(\TypeError::class);
        new SoftDrink($type, $capacity);
    }

    private function provideInvalidData(): array
    {
        return [
            [null, SoftDrinkCapacity::Small],
            [SoftDrinkType::Lemonade, null],
        ];
    }
}