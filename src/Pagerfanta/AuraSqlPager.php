<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Aura\Sql\ExtendedPdoInterface;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Exception\LogicException;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Ray\AuraSqlModule\Annotation\PagerViewOption;
use Ray\AuraSqlModule\Exception\NotInitialized;
// phpcs:ignore SlevomatCodingStandard.Namespaces.UnusedUses.UnusedUse
use ReturnTypeWillChange;

use function assert;
use function class_exists;

/**
 * @template T
 */
class AuraSqlPager implements AuraSqlPagerInterface
{
    private ViewInterface $view;
    private ?RouteGeneratorInterface $routeGenerator = null;

    /** @var array<array<string>> */
    private array $viewOptions;
    private ExtendedPdoInterface $pdo;
    private string $sql;
    private ?string $entity = null;

    /** @var array<mixed> */
    private array $params;

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
     * {@inheritdoc}
     *
     * @phpstan-param positive-int $paging
     */
    public function init(ExtendedPdoInterface $pdo, $sql, array $params, $paging, RouteGeneratorInterface $routeGenerator, ?string $entity = null): void
    {
        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->params = $params;
        $this->paging = $paging;
        $this->routeGenerator = $routeGenerator;
        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    #[ReturnTypeWillChange]
    public function offsetExists($offset): bool
    {
        throw new LogicException('unsupported');
    }

    /**
     * {@inheritdoc}
     *
     * @phpstan-param positive-int $currentPage
     */
    public function offsetGet($currentPage): Page
    {
        if (! $this->routeGenerator instanceof RouteGeneratorInterface) {
            throw new NotInitialized();
        }

        $pagerfanta = new Pagerfanta($this->getPdoAdapter());
        $pagerfanta->setMaxPerPage($this->paging);
        $pagerfanta->setCurrentPage($currentPage);
        $page = new Page($pagerfanta, $this->routeGenerator, $this->view, $this->viewOptions);
        $page->maxPerPage = $pagerfanta->getMaxPerPage();
        $page->current = $pagerfanta->getCurrentPage();
        /** @psalm-suppress UndefinedDocblockClass */
        $page->hasNext = (bool) $pagerfanta->hasNextPage();
        /** @psalm-suppress UndefinedDocblockClass */
        $page->hasPrevious = $pagerfanta->hasPreviousPage();
        $page->data = $pagerfanta->getCurrentPageResults();
        $page->total = $pagerfanta->getNbResults();

        return $page;
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

    /**
     * @return AdapterInterface<T>
     */
    private function getPdoAdapter(): AdapterInterface
    {
        assert($this->entity === null || class_exists($this->entity));
        $fetcher = $this->entity ? new FetchEntity($this->pdo, $this->entity) : new FetchAssoc($this->pdo);

        return new ExtendedPdoAdapter($this->pdo, $this->sql, $this->params, $fetcher);
    }
}
