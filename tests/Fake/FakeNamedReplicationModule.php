<?php

namespace Ray\AuraSqlModule;

use Ray\Di\AbstractModule;

class FakeNamedReplicationModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $user = $password = '';
        $slave = 'slave1,slave2,slave2';
        $this->install(new NamedPdoModule('log_db', 'mysql:host=localhost;dbname=db', $user, $password, $slave));
    }
}
