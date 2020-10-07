<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;

class TransactionalTest extends TestCase
{
    public function testMutipleTransaction()
    {
        $ro = (new Injector(new FakeMultiDbModule()))->getInstance(FakeMultiDb::class);
        /** @var FakeMultiDb $ro */
        $ro->write();
        $users = $ro->read();
        $expected = [
            [
                [
                    'name' => 'koriym',
                    'age' => '18',
                ],
            ],
            [
                [
                    'name' => 'ray',
                    'age' => '19',
                ],
            ],
            [
                [
                    'name' => 'bear',
                    'age' => '20',
                ],
            ],
        ];
        $this->assertSame($expected, $users);
    }
}
