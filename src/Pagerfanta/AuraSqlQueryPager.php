<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlQueryPager implements AuraSqlQueryPagerInterface, \ArrayAccess
{
    private $pdo;

    /**
     * @var ViewInterface
     */
    private $view;

    /**
     * @var RouteGeneratorInterface
     */
    private $routeGenerator;

    /**
     * @var array
     */
    private $viewOptions;

    /**
     * @var SelectInterface
     */
    private $select;

    /**
     * @var int
     */
    private $paging;

    /**
     * @param ViewInterface $view
     * @param array         $viewOptions
     *
     * @PagerViewOption("viewOptions")
     */
    public function __construct(ViewInterface $view, array $viewOptions)
    {
        $this->view = $view;
        $this->viewOptions = $viewOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, RouteGeneratorInterface $routeGenerator)
    {
        $this->pdo = $pdo;
        $this->select = $select;
        $this->paging = $paging;
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($page)
    {
        if (! $this->routeGenerator instanceof RouteGeneratorInterface) {
            throw new NotInitialized();
        }

        $countQueryBuilderModifier = function (Select $select) {
            foreach (\array_keys($select->getCols()) as $key) {
                $select->removeCol($key);
            }

            return $select->cols(['COUNT(*) AS total_results'])->resetOrderBy()->limit(1);
        };
        $pagerfanta = new Pagerfanta(new AuraSqlQueryAdapter($this->pdo, $this->select, $countQueryBuilderModifier));
        $pagerfanta->setMaxPerPage($this->paging);
        $pagerfanta->setCurrentPage($page);

        $pager = new Page($pagerfanta, $this->routeGenerator, $this->view, $this->viewOptions);
        $pager->maxPerPage = $pagerfanta->getMaxPerPage();
        $pager->current = $pagerfanta->getCurrentPage();
        $pager->hasNext = $pagerfanta->hasNextPage();
        $pager->hasPrevious = $pagerfanta->hasPreviousPage();
        $pager->data = $pagerfanta->getCurrentPageResults();
        $pager->total = $pagerfanta->getNbResults();

        return $pager;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        throw new LogicException('unsupported');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException('read only');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('read only');
    }
}
