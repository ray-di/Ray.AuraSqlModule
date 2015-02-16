<?php

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\Read;
use Ray\AuraSqlModule\Annotation\Write;
use Ray\Di\AbstractModule;

class AppModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind()->annotatedWith(Read::class)->toInstance(['read']);
        $this->bind()->annotatedWith(Write::class)->toInstance(['write']);
    }
}
