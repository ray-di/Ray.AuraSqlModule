<?php

namespace Ray\AuraSqlModule\Pagerfanta;

class PdoMySqlAdapterTest extends AbstractPdoTestCase
{
    /**
     * @var ExtendedPdoAdapter
     */
    private $adapter;

    public function setUp()
    {
        parent::setUp();
        $sql = 'SELECT * FROM posts';
        $this->adapter = new ExtendedPdoAdapter($this->pdo, $sql, []);
    }

    public function testGetNbResults()
    {
        $this->assertSame(50, $this->adapter->getNbResults());
    }

    public function testGetResults()
    {
        $expected = [
          [
            'id' => '3',
            0 => '3',
            'username' => 'BEAR',
            1 => 'BEAR',
            'post_content' => 'entry #3',
            2 => 'entry #3',
          ],
          [
              'id' => '4',
              0 => '4',
              'username' => 'BEAR',
              1 => 'BEAR',
              'post_content' => 'entry #4',
              2 => 'entry #4',
          ],
        ];
        $this->assertSame($expected, $this->adapter->getSlice(2, 2));
    }
}
