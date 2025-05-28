<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Iterator;
use IteratorAggregate;
use Override;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;
use Stringable;

/** @implements IteratorAggregate<int, Page> */
final class Page implements IteratorAggregate, Stringable
{
    /** @var int */
    public $maxPerPage;

    /** @var int */
    public $current;

    /** @var int */
    public $total;

    /** @var bool */
    public $hasNext;

    /** @var bool */
    public $hasPrevious;

    /** @var mixed */
    public $data;

    /** @var callable */
    private $routeGenerator;

    /**
     * @phpstan-param Pagerfanta<mixed> $pagerfanta
     * @phpstan-param array<string, mixed>      $viewOption
     */
    public function __construct(
        /** @var Pagerfanta<mixed> */
        private readonly Pagerfanta $pagerfanta,
        RouteGeneratorInterface $routeGenerator,
        private readonly ViewInterface $view,
        /** @var array<string, mixed> */
        private readonly array $viewOption
    ) {
        $this->routeGenerator = $routeGenerator;
    }

    #[Override]
    public function __toString(): string
    {
        return (string) $this->view->render(
            $this->pagerfanta,
            $this->routeGenerator,
            $this->viewOption,
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return Iterator<int, Page>
     */
    #[Override]
    public function getIterator(): Iterator
    {
        /** @var Iterator<int, Page> $iterator */
        $iterator = $this->pagerfanta->getIterator();

        return $iterator;
    }
}
