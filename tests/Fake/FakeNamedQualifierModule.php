<?php

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class FakeNamedQualifierModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new NamedPdoModule('log_db', 'sqlite::memory:', '', '', 'slave1'));
    }
}
