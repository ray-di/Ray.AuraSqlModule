<?php
namespace Ray\AuraSqlModule;

use Ray\Di\Injector;

class TransactionalTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \Ray\AuraSqlModule\Exception\InvalidTransactionalPropertyException
     */
    public function testInvalidPropertyException()
    {
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
