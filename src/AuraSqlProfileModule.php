<?php

declare(strict_types=1);

namespace Ray\AuraSqlModule;

use Aura\Sql\Profiler\ProfilerInterface;
use Ray\Di\AbstractModule;

class AuraSqlProfileModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(ProfilerInterface::class)->toProvider(ProfilerProvider::class);
    }
}
