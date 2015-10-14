<?php
/**
 * This file is part of the Ray.AuraSqlModule package
 *
 * @license http://opensource.org/licenses/bsd-license.php BSD
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
