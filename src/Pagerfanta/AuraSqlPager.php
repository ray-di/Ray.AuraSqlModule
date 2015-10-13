<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\Di\Di\Named;

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
     * @var
     */
    private $sql;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $paging;

    /**
     * @param ViewInterface           $view
     * @param RouteGeneratorInterface $routeGenerator
     * @param array                   $viewOptions
     *
     * @Named("viewOptions=view_options")
     */
    public function __construct(ViewInterface $view, RouteGeneratorInterface $routeGenerator, array $viewOptions)
    {
        $this->view = $view;
        $this->routeGenerator = $routeGenerator;
        $this->viewOptions = $viewOptions;
    }

    /**
     * {@inheritdoc}
     */
    public function init(ExtendedPdoInterface $pdo, $sql, $page, $paging)
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->page = $page;
        $this->paging = $paging;
    }

    /**
     * @param array $params
     *
     * @return Pager
     */
    public function execute(array $params)
    {
        $pagerfanta = new Pagerfanta(new ExtendedPdoAdapter($this->pdo, $this->sql, $params));
        $pagerfanta->setCurrentPage($this->page);
        $pagerfanta->setMaxPerPage($this->paging);
        $pager = new Pager($pagerfanta);
        $pager->maxPerPage = $pagerfanta->getMaxPerPage();
        $pager->current = $pagerfanta->getCurrentPage();
        $pager->hasNext = $pagerfanta->hasNextPage();
        $pager->hasPrevious = $pagerfanta->hasPreviousPage();
        $pager->data = $pagerfanta->getCurrentPageResults();
        $pager->html = $this->getHtml($pagerfanta);

        return $pager;
    }

    /**
     * Return html
     *
     * @return string
     */
    private function getHtml(Pagerfanta $pagerfanta)
    {
        $html = $this->view->render(
            $pagerfanta,
            $this->routeGenerator,
            $this->viewOptions
        );
        return $html;
    }
}
