<?php

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class FakeNamedModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new NamedPdoModule('log_db', 'sqlite::memory:'));
    }
}
