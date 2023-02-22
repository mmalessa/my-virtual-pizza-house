<?php

declare(strict_types=1);

namespace App\Tests\Unit\OrderManager\Application\Message\Menu\Event;

use App\Menu\Application\Message\Menu\Event\MenuGot;
use App\OrderManager\Application\Message\Waiter\Event\OrderPlaced;
use PHPUnit\Framework\TestCase;

class MenuGotTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testMenuGot(string $sagaId, array $menu)
    {
        $menuGot = new MenuGot($sagaId, $menu);
        $this->assertEquals($sagaId, $menuGot->sagaId);
        $this->assertEquals($menu, $menuGot->menu);
    }

    public static function provideValidData(): array
    {
        return [
            ['64821cee-659f-485d-9bf3-fd8bab722cda', []],
            ['64821cee-659f-485d-9bf3-fd8bab722cda', ['a', 'b', 'c']],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testMenuGotError(string $sagaId, array $menu)
    {
        $this->expectException(\InvalidArgumentException::class);
        new MenuGot($sagaId, $menu);
    }

    public static function provideInvalidData(): array
    {
        return [
            ['', []],
            ['sagaId', ['a', 'b', 'c']]
        ];
    }
}