<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

interface RouteGeneratorInterface
{
    /**
     * @param mixed $page
     * @phpstan-param int $page;
     *
     * @phpstan-return string
     */
    public function __invoke($page);
}
