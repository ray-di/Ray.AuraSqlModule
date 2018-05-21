<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

class DefaultRouteGenerator implements RouteGeneratorInterface
{
    private $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    public function __invoke($page)
    {
        return uri_template($this->uri, ['page' => $page]);
    }
}
