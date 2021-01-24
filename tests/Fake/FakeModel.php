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
#[AuraSql]
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
        return true;
    }

    public function write()
    {
        return true;
    }

    /**
     * @ReadOnlyConnection
     */
    #[ReadOnlyConnection]
    public function slave()
    {
        return true;
    }

    /**
     * @WriteConnection
     * @Transactional
     */
    #[WriteConnection, Transactional]
    public function master()
    {
        return true;
    }

    /**
     * @WriteConnection
     * @Transactional
     */
    #[WriteConnection, Transactional]
    public function dbError()
    {
        $this->pdo->exec('xxx');
    }

    /**
     * @Transactional
     */
    #[Transactional]
    public function noDb()
    {
        $this->pdo->exec('xxx');
    }
}
