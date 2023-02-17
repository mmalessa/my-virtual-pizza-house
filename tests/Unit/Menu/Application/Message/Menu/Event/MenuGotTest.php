<?php

declare(strict_types=1);

namespace App\Tests\Unit\Menu\Application\Message\Menu\Event;

use App\Menu\Application\Message\Menu\Event\MenuGot;
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

    private function provideValidData(): array
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

    private function provideInvalidData(): array
    {
        return [
            ['', []],
            ['sagaId', ['a', 'b', 'c']]
        ];
    }
}