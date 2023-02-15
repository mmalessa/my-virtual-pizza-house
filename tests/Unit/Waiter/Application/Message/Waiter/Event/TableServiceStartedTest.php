<?php

declare(strict_types=1);

namespace App\Tests\Unit\Waiter\Application\Message\Waiter\Event;

use App\Waiter\Application\Message\Waiter\Event\TableServiceStarted;
use PHPUnit\Framework\TestCase;

class TableServiceStartedTest extends TestCase
{
    /** @dataProvider provideValidData */
    public function testTableServiceStarted(string $tableId)
    {
        $tableServiceStarted = new TableServiceStarted($tableId);
        $this->assertEquals($tableId, $tableServiceStarted->tableId);
    }

    private function provideValidData(): array
    {
        return [
            ['T1'],
            ['Table1']
        ];
    }

    /** @dataProvider provideInvalidData */
    public function testTableServiceStartedError(string $tableId)
    {
        $this->expectException(\InvalidArgumentException::class);
        new TableServiceStarted($tableId);
    }

    private function provideInvalidData(): array
    {
        return [
          ['']
        ];
    }
}