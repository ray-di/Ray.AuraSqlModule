<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

class DefaultRouteGenerator implements RouteGeneratorInterface
{
    private string $uri;

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
