<?php
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\Exception\LogicException;
use Pagerfanta\View\DefaultView;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlQueryPagerTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->pager = new AuraSqlQueryPager(new DefaultView, []);
    }

    public function testExecute()
    {
        $this->setExpectedException(NotInitialized::class);
        $pager = $this->pager;
        $pager[1];
    }

    public function testOffsetExists()
    {
        $this->setExpectedException(LogicException::class);
        $pager = $this->pager;
        isset($pager[1]);
    }

    public function testOffsetSet()
    {
        $this->setExpectedException(LogicException::class);
        $pager = $this->pager;
        $pager[1] = 1;
    }

    public function testOffsetUnset()
    {
        $this->setExpectedException(LogicException::class);
        $pager = $this->pager;
        unset($pager[1]);
    }
}
