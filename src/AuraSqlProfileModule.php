<?php

declare(strict_types=1);

/**
 * This file is part of the Ray.AuraSqlModule package.
 */

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
