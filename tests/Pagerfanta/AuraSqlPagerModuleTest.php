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
        $pager = $factory->newInstance($this->pdo, $sql, 1, '/?page={page}&category=sports');
        $user = $pager->execute([], 2);
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
        $expected = '<nav><a href="/?page=1">Previous</a><a href="/?page=1">1</a><span class="current">2</span><a href="/?page=3">3</a><a href="/?page=4">4</a><a href="/?page=5">5</a><span class="dots">...</span><a href="/?page=50">50</a><a href="/?page=3">Next</a></nav>';
        $this->assertSame($expected, $user->html);
    }
}
