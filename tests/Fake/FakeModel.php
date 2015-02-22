<?php

namespace Ray\AuraSqlModule;

use Aura\Sql\ExtendedPdo;
use Ray\AuraSqlModule\Annotation\AuraSql;
use Ray\AuraSqlModule\Annotation\ReadOnlyConnection;
use Ray\AuraSqlModule\Annotation\Transactional;
use Ray\AuraSqlModule\Annotation\WriteConnection;

/**
 * @AuraSql
 */
class FakeModel
{

    /**
     * @var ExtendedPdo
     */
    protected $pdo;

    public function getPdo()
    {
        return $this->pdo;
    }

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
     * @Transactional
     */
    public function master()
    {
    }

    /**
     * @WriteConnection
     * @Transactional
     */
    public function dbError()
    {
        $this->pdo->exec('xxx');
    }

    /**
     * @Transactional
     */
    public function noDb()
    {
        $this->pdo->exec('xxx');
    }

}
