<?php

namespace Ray\AuraSqlModule;

use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\WriteConnection;

/**
 * @AuraSql
 */
class FakeModel
{
    public $pdo;

    public function read()
    {
    }

    public function write()
    {
    }

    /**
     * @ReadOnlyConnection
     */
    public function slave()
    {
    }

    /**
     * @WriteConnection
     */
    public function master()
    {
    }
}
