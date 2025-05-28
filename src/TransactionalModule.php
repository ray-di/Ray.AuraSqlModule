<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Override;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\Di\AbstractModule;

class TransactionalModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        // @Transactional
        $this->bindInterceptor(
            $this->matcher->any(),
            $this->matcher->annotatedWith(Transactional::class),
            [TransactionalInterceptor::class],
        );
    }
}
