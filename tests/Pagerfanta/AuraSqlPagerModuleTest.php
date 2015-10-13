<?php
namespace Ray\AuraSqlModule\Pagerfanta;

use Ray\Di\Injector;

class AuraSqlPagerModuleTest extends AbstractPdoTestCase
{

    public function testNewInstance()
    {
        $factory = (new Injector(new AuraSqlPagerModule()))->getInstance(AuraSqlPagerFactoryInterface::class);
        /** @var $factory AuraSqlPagerFactoryInterface */
        $this->assertInstanceOf(AuraSqlPagerFactory::class, $factory);
        $sql = 'SELECT * FROM posts';
        $pager = $factory->newInstance($this->pdo, $sql, 2, 1);
        $user = $pager->execute([]);
        $this->assertTrue($user->hasNext);
        $this->assertTrue($user->hasPrevious);
        $expected = [
                [
                    'id' => '2',
                    'username' => 'BEAR',
                    'post_content' => 'entry #2',
                ],
        ];
        $this->assertSame($expected, $user->data);
    }

}
