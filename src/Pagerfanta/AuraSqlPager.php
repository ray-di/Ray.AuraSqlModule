<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlPager implements AuraSqlPagerInterface
{
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
     * @var ExtendedPdoInterface
     */
    private $pdo;

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $params;

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
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator)
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->params = $params;
        $this->paging = $paging;
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * @param int $page
     *
     * @return Page
     */
    private function execute($page)
    {
        if (! $this->routeGenerator instanceof RouteGeneratorInterface) {
            throw new NotInitialized();
        }
        $pagerfanta = new Pagerfanta(new ExtendedPdoAdapter($this->pdo, $this->sql, $this->params));
        $pagerfanta->setCurrentPage($page);
        $pagerfanta->setMaxPerPage($this->paging);
        $page = new Page($pagerfanta, $this->routeGenerator, $this->view, $this->viewOptions);
        $page->maxPerPage = $pagerfanta->getMaxPerPage();
        $page->current = $pagerfanta->getCurrentPage();
        $page->hasNext = $pagerfanta->hasNextPage();
        $page->hasPrevious = $pagerfanta->hasPreviousPage();
        $page->data = $pagerfanta->getCurrentPageResults();

        return $page;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset)
    {
        throw new LogicException('unsupported');
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset)
    {
        return $this->execute($offset);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value)
    {
        throw new LogicException('read only');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset)
    {
        throw new LogicException('read only');
    }
}
