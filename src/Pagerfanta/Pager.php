<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\Pagerfanta;

final class Pager
{
    /**
     * @var int
     */
    public $maxPerPage;

    /**
     * @var int
     */
    public $current;

    /**
     * @var int
     */
    public $total;

    /**
     * @var bool
     */
    public $hasNext;

    /**
     * @var bool
     */
    public $hasPrevious;

    /**
     * @var string
     */
    public $html;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @var Pagerfanta
     */
    public $pagerfanta;

    public function __construct(Pagerfanta $pagerfanta)
    {
        $this->pagerfanta = $pagerfanta;
    }
}
