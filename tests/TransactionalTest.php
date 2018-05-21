<?php
namespace Ray\AuraSqlModule;

use PHPUnit\Framework\TestCase;
use Ray\AuraSqlModule\Exception\InvalidTransactionalPropertyException;
use Ray\Di\Injector;

class TransactionalTest extends TestCase
{
    public function testInvalidPropertyException()
    {
        $this->expectException(InvalidTransactionalPropertyException::class);
        $ro = (new Injector(new FakeMultiDbModule))->getInstance(FakeMultiDb::class);
        /* @var $ro FakeMultiDb */
        $ro->noProp();
    }

    public function testMutipleTransaction()
    {
        $ro = (new Injector(new FakeMultiDbModule))->getInstance(FakeMultiDb::class);
        /* @var $ro FakeMultiDb */
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
