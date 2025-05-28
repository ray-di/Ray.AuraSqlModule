<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule\Pagerfanta;

use Override;

final readonly class DefaultRouteGenerator implements RouteGeneratorInterface
{
    public function __construct(private string $uri)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[Override]
    public function __invoke($page)
    {
        return uri_template($this->uri, ['page' => $page]);
    }
}
