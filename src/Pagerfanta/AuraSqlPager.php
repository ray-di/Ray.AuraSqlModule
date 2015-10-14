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
     * @var
     */
    private $sql;

    /**
     * @var int
     */
    private $paging;

    /**
     * @param ViewInterface           $view
     * @param RouteGeneratorInterface $routeGenerator
     * @param array                   $viewOptions
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
    public function init(ExtendedPdoInterface $pdo, $sql, $paging, RouteGeneratorInterface $routeGenerator)
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->paging = $paging;
        $this->routeGenerator = $routeGenerator;
    }

    /**
     * @param array $params
     * @param int   $page
     *
     * @return Pager
     */
    public function execute(array $params, $page)
    {
        if (! $this->routeGenerator instanceof RouteGeneratorInterface) {
            throw new NotInitialized();
        }
        $pagerfanta = new Pagerfanta(new ExtendedPdoAdapter($this->pdo, $this->sql, $params));
        $pagerfanta->setCurrentPage($page);
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
