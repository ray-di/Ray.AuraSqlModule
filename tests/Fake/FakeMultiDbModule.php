<?php

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class FakeMultiDbModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new TransactionalModule);
        $this->bind(FakeMultiDb::class);
        $this->install(new NamedPdoModule('pdo1', 'sqlite::memory:'));
        $this->install(new NamedPdoModule('pdo2', 'sqlite::memory:'));
        $this->install(new NamedPdoModule('pdo3', 'sqlite::memory:'));
    }
}
