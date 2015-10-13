<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
namespace Ray\AuraSqlModule\Pagerfanta;

class DefaultRouteGenerator implements RouteGeneratorInterface
{
    public function __invoke($page)
    {
        return "?page={$page}";
    }
}
