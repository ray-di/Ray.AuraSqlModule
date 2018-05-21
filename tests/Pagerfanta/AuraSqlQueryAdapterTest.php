<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\SqlQuery\Common\Select;
use Pagerfanta\Pagerfanta;

class AuraSqlQueryAdapterTest extends AuraSqlQueryTestCase
{
    public function testGetNbResults()
    {
        $adapter = $this->createAdapterToTestGetNbResults();

        $this->doTestGetNbResults($adapter);
    }

    public function testGetNbResultsShouldWorkAfterCallingGetSlice()
    {
        $adapter = $this->createAdapterToTestGetNbResults();

        $adapter->getSlice(1, 10);

        $this->doTestGetNbResults($adapter);
    }

    public function testGetSlice()
    {
        $adapter = $this->createAdapterToTestGetSlice();

        $this->doTestGetSlice($adapter);
    }

    public function testGetSliceShouldWorkAfterCallingGetNbResults()
    {
        $adapter = $this->createAdapterToTestGetSlice();

        $adapter->getNbResults();

        $this->doTestGetSlice($adapter);
    }

    public function testUsage()
    {
        $adapter = $this->createAdapterToTestGetNbResults();
        $pagerfanta = new Pagerfanta($adapter);
        $maxPerPage = 2;
        $pagerfanta->setMaxPerPage($maxPerPage);
        $maxPerPage = $pagerfanta->getMaxPerPage();
        $this->assertSame(2, $maxPerPage);

        $currentPage = 2;
        $pagerfanta->setCurrentPage($currentPage);
        $currentPage = $pagerfanta->getCurrentPage();
        $this->assertSame(2, $currentPage);

        $nbResults = $pagerfanta->getNbResults();
        $this->assertSame(50, $nbResults);

        $currentPageResults = $pagerfanta->getCurrentPageResults();
        $expected = [
                [
                    'id' => '3',
                    'username' => 'Jon Doe',
                    'post_content' => 'Post #3',
                ],
                [
                    'id' => '4',
                    'username' => 'Jon Doe',
                    'post_content' => 'Post #4',
                ],
        ];
        $this->assertSame($expected, $currentPageResults);
    }

    private function doTestGetNbResults(AuraSqlQueryAdapter $adapter)
    {
        $this->assertSame(50, $adapter->getNbResults());
    }

    private function createAdapterToTestGetSlice()
    {
        $countQueryBuilderModifier = function () {
        };

        return new AuraSqlQueryAdapter($this->pdo, $this->select, $countQueryBuilderModifier);
    }

    private function doTestGetSlice(AuraSqlQueryAdapter $adapter)
    {
        $offset = 30;
        $length = 10;

        $select = clone $this->select;
        $select->offset($offset)->limit($length);

        $sth = $this->pdo->prepare($select->getStatement());
        $sth->execute();
        $expectedResults = $sth->fetchAll(\PDO::FETCH_ASSOC);
        $results = $adapter->getSlice($offset, $length);

        $this->assertSame($expectedResults, $results);
    }

    private function createAdapterToTestGetNbResults()
    {
        $countQueryBuilderModifier = function (Select $select) {
            foreach (\array_keys($select->getCols()) as $key) {
                $select->removeCol($key);
            }

            return $select->cols(['COUNT(*) AS total_results'])->limit(1);
        };

        return new AuraSqlQueryAdapter($this->pdo, $this->select, $countQueryBuilderModifier);
    }
}
