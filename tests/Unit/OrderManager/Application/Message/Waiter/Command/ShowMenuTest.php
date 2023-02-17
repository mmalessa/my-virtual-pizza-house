<?php

declare(strict_types=1);

namespace App\Tests\Unit\OrderManager\Application\Message\Waiter\Command;

use App\OrderManager\Application\Message\Waiter\Command\ShowMenu;
use PHPUnit\Framework\TestCase;

class ShowMenuTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testShowMenu(string $sagaId, array $menu)
    {
        $showMenu = new ShowMenu($sagaId, $menu);
        $this->assertEquals($sagaId, $showMenu->sagaId);
        $this->assertEquals($menu, $showMenu->menu);
    }

    private function provideValidData(): array
    {
        return [
            ['sagaId', []],
            ['sagaId', ['some', 'array']]
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testShowMenuError(string $sagaId, array $menu)
    {
        $this->expectException(\InvalidArgumentException::class);
        new ShowMenu($sagaId, $menu);
    }

    private function provideInvalidData(): array
    {
        return [
            ['', []],
            ['', ['some', 'array']]
        ];
    }
}