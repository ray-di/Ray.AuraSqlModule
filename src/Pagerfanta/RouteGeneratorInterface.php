<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
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
