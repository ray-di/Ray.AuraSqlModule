<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\AuraSqlModule\Exception\NotInitialized;

class AuraSqlQueryPager implements AuraSqlQueryPagerInterface
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
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, $paging, RouteGeneratorInterface $routeGenerator)
    {
        $this->pdo = $pdo;
        $this->select = $select;
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

        $countQueryBuilderModifier = function (SelectInterface $select) {
            return $select->cols(['COUNT(*) AS total_results'])->limit(1);
        };
        $pagerfanta = new Pagerfanta(new AuraSqlQueryAdapter($this->pdo, $this->select, $countQueryBuilderModifier));
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
