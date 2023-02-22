<?php

declare(strict_types=1);

namespace App\Tests\Unit\OrderManager\Application\Message\Menu\Command;

use App\OrderManager\Application\Message\Menu\Command\GetMenu;
use PHPUnit\Framework\TestCase;

class GetMenuTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testGetMenu(string $sagaId)
    {
        $getMenu = new \App\Menu\Application\Message\Menu\Command\GetMenu($sagaId);
        $this->assertEquals($sagaId, $getMenu->sagaId);
    }

    public static function provideValidData(): array
    {
        return [
            ['8ed543d7-a6ad-4746-8c11-7b27437c0d38'],
            ['c9c18f74-4f08-4cd4-bdaa-702bdc5594b9'],
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testGetMenuError(string $sagaId)
    {
        $this->expectException(\InvalidArgumentException::class);
        new GetMenu($sagaId);
    }

    public static function provideInvalidData(): array
    {
        return [
            [''],
            ['c9c18f74-4f0-4cd4-bdaa-702bdc5594b9'],
            ['64821cee-659f'],
        ];
    }
}