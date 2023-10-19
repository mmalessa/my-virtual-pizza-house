<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\Message\Waiter\Command;

use App\Waiter\Application\Message\Waiter\Command\ShowMenu;
use PHPUnit\Framework\TestCase;

class ShowMenuTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testShowMenu(string $sagaId, array $menu)
    {
        $showMenu = new ShowMenu($sagaId, $menu);
        $this->assertEquals($sagaId, $showMenu->processId);
        $this->assertEquals($menu, $showMenu->menu);
    }

    public static function provideValidData(): array
    {
        return [
            ['64821cee-659f-485d-9bf3-fd8bab722cda', []],
            ['64821cee-659f-485d-9bf3-fd8bab722cda', ['some', 'array']]
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testShowMenuError(string $sagaId, array $menu)
    {
        $this->expectException(\InvalidArgumentException::class);
        new ShowMenu($sagaId, $menu);
    }

    public static function provideInvalidData(): array
    {
        return [
            ['', []],
            ['', ['some', 'array']]
        ];
    }
}
