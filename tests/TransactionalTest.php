<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdoInterface;
use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
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

    public function testMutipleTransactionBindNull()
    {
        $module = new FakeMultiDbModule();
        $module->override(new class extends AbstractModule{
            protected function configure()
            {
                $this->bind(ExtendedPdoInterface::class)->toInstance(null);
            }

        });
        $ro = (new Injector($module))->getInstance(FakeMultiDb::class);
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

    public function testMutipleTransactionBindNullTransactionNoValue()
    {
        $module = new FakeMultiDbModule();
        $module->override(new class extends AbstractModule{
            protected function configure()
            {
                $this->bind(ExtendedPdoInterface::class)->toInstance(null);
            }

        });
        $ro = (new Injector($module))->getInstance(FakeMultiDb::class);
        $ro->writeNoValueTransactional();
        $users = $ro->read();
        $expected = [
            [
                [
                    'name' => 'koriym',
                    'age' => '18',
                ],
            ],
            [],
            []
        ];
        $this->assertSame($expected, $users);
    }
}
