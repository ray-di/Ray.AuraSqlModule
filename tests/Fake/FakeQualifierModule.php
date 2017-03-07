<?php

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class FakeQualifierModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new AuraSqlModule('sqlite::memory:', '', '', 'slave1'));
    }
}
