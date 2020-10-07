<?php
namespace Ray\AuraSqlModule\Pagerfanta;

interface RouteGeneratorInterface
{
    /**
     * @phpstan-param int $page;
     * @phpstan-return string
     *
     * @param mixed $page
     */
    public function __invoke($page);
}
