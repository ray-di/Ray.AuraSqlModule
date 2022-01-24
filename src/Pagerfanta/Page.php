<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Iterator;
use IteratorAggregate;
use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;

/**
 * @implements IteratorAggregate<int, Page>
 */
final class Page implements IteratorAggregate
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

    /** @var Pagerfanta<mixed> */
    private $pagerfanta;

    /** @var callable */
    private $routeGenerator;

    /** @var ViewInterface */
    private $view;

    /** @var array<string, mixed> */
    private $viewOption;

    /**
     * @phpstan-param Pagerfanta<mixed> $pagerfanta
     * @phpstan-param array<string, mixed>      $viewOption
     */
    public function __construct(
        Pagerfanta $pagerfanta,
        RouteGeneratorInterface $routeGenerator,
        ViewInterface $view,
        array $viewOption
    ) {
        $this->pagerfanta = $pagerfanta;
        $this->routeGenerator = $routeGenerator;
        $this->view = $view;
        $this->viewOption = $viewOption;
    }

    public function __toString(): string
    {
        return (string) $this->view->render(
            $this->pagerfanta,
            $this->routeGenerator,
            $this->viewOption
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        /** @var Iterator<int, Page> $iterator */
        $iterator = $this->pagerfanta->getIterator();

        return $iterator;
    }
}
