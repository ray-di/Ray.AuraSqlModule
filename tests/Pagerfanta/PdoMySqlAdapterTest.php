<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

class PdoMySqlAdapterTest extends AbstractPdoTestCase
{
    /**
     * @var ExtendedPdoAdapter
     */
    private $adapter;

    public function setUp() : void
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
            'username' => 'BEAR',
            'post_content' => 'entry #3',
          ],
          [
              'id' => '4',
              'username' => 'BEAR',
              'post_content' => 'entry #4',
          ],
        ];
        $this->assertSame($expected, $this->adapter->getSlice(2, 2));
    }
}
