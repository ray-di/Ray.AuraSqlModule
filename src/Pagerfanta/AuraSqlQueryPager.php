<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use ArrayAccess;
use Aura\Sql\ExtendedPdoInterface;
use Aura\SqlQuery\Common\Select;
use Aura\SqlQuery\Common\SelectInterface;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\AuraSqlModule\Exception\NotInitialized;

use function array_keys;

/**
 * @implements ArrayAccess<int, Page>
 */

class AuraSqlQueryPager implements AuraSqlQueryPagerInterface, ArrayAccess
{
    private ExtendedPdoInterface $pdo;
    private ViewInterface $view;
    private ?RouteGeneratorInterface $routeGenerator = null;

    /** @var array<array<string>> */
    private array $viewOptions;
    private SelectInterface $select;

    /** @phpstan-var positive-int */
    private int $paging;

    /**
     * @param array<array<string>> $viewOptions
     *
     * @PagerViewOption("viewOptions")
     */
    #[PagerViewOption('viewOptions')]
    public function __construct(ViewInterface $view, array $viewOptions)
    {
        $this->view = $view;
        $this->viewOptions = $viewOptions;
    }

    /**
     * @phpstan-param positive-int $paging
     * {@inheritdoc}
     */
    public function init(ExtendedPdoInterface $pdo, SelectInterface $select, int $paging, RouteGeneratorInterface $routeGenerator)
    {
        $this->pdo = $pdo;
        $this->select = $select;
        $this->paging = $paging;
        $this->routeGenerator = $routeGenerator;

        return $this;
    }

    /**
     * @phpstan-param positive-int $page
     * {@inheritdoc}
     */
    public function offsetGet($page): Page
    {
        if (! $this->routeGenerator instanceof RouteGeneratorInterface) {
            throw new NotInitialized();
        }

        $countQueryBuilderModifier = static function (Select $select) {
            foreach (array_keys($select->getCols()) as $key) {
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
        /** @psalm-suppress UndefinedDocblockClass */
        $pager->hasNext = $pagerfanta->hasNextPage();
        /** @psalm-suppress UndefinedDocblockClass */
        $pager->hasPrevious = $pagerfanta->hasPreviousPage();
        $pager->data = $pagerfanta->getCurrentPageResults();
        $pager->total = $pagerfanta->getNbResults();

        return $pager;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        throw new LogicException('unsupported');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        throw new LogicException('read only');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        throw new LogicException('read only');
    }
}
