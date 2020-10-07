<?php
namespace Ray\AuraSqlModule\Pagerfanta;

use Pagerfanta\Pagerfanta;
use Pagerfanta\View\ViewInterface;

/**
 * @implements \IteratorAggregate<int, Page>
 */
final class Page implements \IteratorAggregate
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
     * @var mixed
     */
    public $data;

    /**
     * @var Pagerfanta<int, array>
     */
    private $pagerfanta;

    /**
     * @var callable
     */
    private $routeGenerator;

    /**
     * @var ViewInterface
     */
    private $view;

    /**
     * @var array<string, mixed>
     */
    private $viewOption;

    /**
     * @phpstan-param Pagerfanta<int, array> $pagerfanta
     * @phpstan-param array<array>      $viewOption
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

    public function __toString()
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
        return $this->pagerfanta->getIterator();
    }
}
