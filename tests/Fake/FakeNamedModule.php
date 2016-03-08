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
        $this->install(new NamedPdoModule(FakeLogDb::class, 'sqlite::memory:'));
        $this->install(new NamedPdoModule(FakeLogDbInject::class, 'sqlite::memory:'));
    }
}
