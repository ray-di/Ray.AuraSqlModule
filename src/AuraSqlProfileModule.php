<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\Profiler\ProfilerInterface;
use Override;
use Ray\Di\AbstractModule;

class AuraSqlProfileModule extends AbstractModule
{
    /**
     * {@inheritDoc}
     */
    #[Override]
    protected function configure(): void
    {
        $this->bind(ProfilerInterface::class)->toProvider(ProfilerProvider::class);
    }
}
