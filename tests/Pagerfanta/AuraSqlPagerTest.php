<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\Exception\LogicException;
use Pagerfanta\View\DefaultView;
use PHPUnit\Framework\TestCase;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlPagerTest extends TestCase
{
    /** @var AuraSqlPager */
    private $pager;

    public function setUp(): void
    {
        parent::setUp();
        $this->pager = new AuraSqlPager(new DefaultView(), []);
    }

    public function testExecute()
    {
        $this->expectException(NotInitialized::class);
        $pager = $this->pager;
        $pager[1]; // @phpstan-ignore-line
    }

    public function testOffsetExists()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        isset($pager[1]); // @phpstan-ignore-line
    }

    public function testOffsetSet()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        $pager[1] = 1;
    }

    public function testOffsetUnset()
    {
        $this->expectException(LogicException::class);
        $pager = $this->pager;
        unset($pager[1]);
    }
}
