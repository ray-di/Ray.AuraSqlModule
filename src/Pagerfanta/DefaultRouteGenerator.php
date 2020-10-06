<?php
/**
 * This file is part of the Ray.AuraSqlModule package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace Ray\AuraSqlModule\Pagerfanta;

class DefaultRouteGenerator implements RouteGeneratorInterface
{
    /**
     * @var string
     */
    private $uri;

    public function __construct(string $uri)
    {
        $this->uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke($page)
    {
        return uri_template($this->uri, ['page' => $page]);
    }
}
