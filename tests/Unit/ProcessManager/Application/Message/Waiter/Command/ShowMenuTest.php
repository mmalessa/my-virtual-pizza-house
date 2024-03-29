<?php

declare(strict_types=1);

namespace App\Tests\Unit\ProcessManager\Application\Message\Waiter\Command;

use App\ProcessManager\Application\Message\Waiter\Command\ShowMenu;
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
            ['c3b9b891-ba30-40cd-b584-5a32b9184b05', []],
            ['c3b9b891-ba30-40cd-b584-5a32b9184b05', ['some', 'array']]
        ];
    }
}
